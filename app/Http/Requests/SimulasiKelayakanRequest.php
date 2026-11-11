<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class SimulasiKelayakanRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return $user !== null && $user->isDesa() && $user->desa()->exists();
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
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
                    'kk_terdampak',
                    'hari_tanpa_air',
                    'jarak_sumber_meter',
                ])) {
                    return;
                }

                $desa = $this->user()?->desa;
                if ($desa === null) {
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
                            'kk_terdampak',
                            'Data kependudukan desa belum lengkap. Lengkapi profil desa sebelum melakukan pemeriksaan.'
                        );

                        return;
                    }
                }

                if ((int) $desa->jumlah_kk_total < 1 || (int) $desa->jumlah_jiwa_total < 1) {
                    $validator->errors()->add(
                        'kk_terdampak',
                        'Jumlah KK dan jumlah jiwa pada profil desa harus lebih dari nol.'
                    );

                    return;
                }

                foreach (['jumlah_balita', 'jumlah_lansia', 'jumlah_disabilitas'] as $field) {
                    if ((int) $desa->{$field} < 0) {
                        $validator->errors()->add(
                            'kk_terdampak',
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
            'kk_terdampak' => 'jumlah KK terdampak',
            'hari_tanpa_air' => 'hari tanpa air',
            'jarak_sumber_meter' => 'jarak sumber air',
        ];
    }
}
