<?php

namespace App\Http\Requests;

use App\Models\Desa;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class SimulasiKelayakanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'kecamatan' => ['required', 'string', 'exists:desas,kecamatan'],
            'kode_desa' => ['required', 'string', 'exists:desas,kode_desa'],
            'kk_terdampak' => ['required', 'integer', 'min:0'],
            'hari_tanpa_air' => ['required', 'integer', 'min:0'],
            'jarak_sumber_meter' => ['required', 'integer', 'min:0'],
        ];
    }

    /**
     * @return array<int, callable>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->hasAny([
                    'kecamatan',
                    'kode_desa',
                    'kk_terdampak',
                    'hari_tanpa_air',
                    'jarak_sumber_meter',
                ])) {
                    return;
                }

                $desa = Desa::query()->find($this->input('kode_desa'));
                if ($desa === null) {
                    return;
                }

                if ($desa->kecamatan !== $this->input('kecamatan')) {
                    $validator->errors()->add(
                        'kode_desa',
                        'Desa yang dipilih tidak sesuai dengan kecamatan.'
                    );

                    return;
                }

                $dataProfil = [
                    'jumlah_kk_total',
                    'jumlah_jiwa_total',
                    'jumlah_balita',
                    'jumlah_lansia',
                    'jumlah_disabilitas',
                ];
                foreach ($dataProfil as $field) {
                    if ($desa->{$field} === null) {
                        $validator->errors()->add(
                            'kode_desa',
                            'Data kependudukan desa belum lengkap. Lengkapi profil desa sebelum melakukan pemeriksaan.'
                        );

                        return;
                    }
                }

                if ((int) $desa->jumlah_kk_total < 1 || (int) $desa->jumlah_jiwa_total < 1) {
                    $validator->errors()->add(
                        'kode_desa',
                        'Jumlah KK dan jumlah jiwa pada profil desa harus lebih dari nol.'
                    );

                    return;
                }

                foreach (['jumlah_balita', 'jumlah_lansia', 'jumlah_disabilitas'] as $field) {
                    if ((int) $desa->{$field} < 0) {
                        $validator->errors()->add(
                            'kode_desa',
                            'Data kelompok rentan pada profil desa tidak boleh bernilai negatif.'
                        );

                        return;
                    }
                }

                if ((int) $this->input('kk_terdampak') > (int) $desa->jumlah_kk_total) {
                    $validator->errors()->add(
                        'kk_terdampak',
                        'Jumlah KK terdampak tidak boleh melebihi jumlah KK total.'
                    );
                }

                $jumlahRentan = (int) $desa->jumlah_balita
                    + (int) $desa->jumlah_lansia
                    + (int) $desa->jumlah_disabilitas;

                if ($jumlahRentan > (int) $desa->jumlah_jiwa_total) {
                    $validator->errors()->add(
                        'jumlah_disabilitas',
                        'Total kelompok rentan tidak boleh melebihi jumlah jiwa total.'
                    );
                }
            },
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            '*.required' => 'Kolom ini wajib diisi.',
            '*.integer' => 'Masukkan jumlah berupa bilangan bulat.',
            '*.min' => 'Nilai tidak boleh negatif; jumlah total harus lebih dari nol.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'kecamatan' => 'kecamatan',
            'kode_desa' => 'desa',
            'kk_terdampak' => 'jumlah KK terdampak',
            'hari_tanpa_air' => 'hari tanpa air',
            'jarak_sumber_meter' => 'jarak sumber air',
        ];
    }
}
