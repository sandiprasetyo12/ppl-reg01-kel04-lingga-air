<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKoordinatDesaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isBpbd() ?? false;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'latitude' => ['required', 'numeric', 'between:-7.55,-7.15'],
            'longitude' => ['required', 'numeric', 'between:109.15,109.65'],
            'titik_drop_air' => ['nullable', 'string', 'max:150'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'latitude.required' => 'Koordinat garis lintang (latitude) wajib diisi.',
            'latitude.numeric' => 'Garis lintang (latitude) harus berupa angka desimal yang valid.',
            'latitude.between' => 'Latitude harus berada dalam batas wilayah Kabupaten Purbalingga (-7.55 s.d. -7.15).',
            'longitude.required' => 'Koordinat garis bujur (longitude) wajib diisi.',
            'longitude.numeric' => 'Garis bujur (longitude) harus berupa angka desimal yang valid.',
            'longitude.between' => 'Longitude harus berada dalam batas wilayah Kabupaten Purbalingga (109.15 s.d. 109.65).',
            'titik_drop_air.string' => 'Lokasi titik drop air harus berupa teks yang valid.',
            'titik_drop_air.max' => 'Lokasi titik drop air maksimal 150 karakter.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'latitude' => 'latitude',
            'longitude' => 'longitude',
            'titik_drop_air' => 'titik drop air',
        ];
    }
}

