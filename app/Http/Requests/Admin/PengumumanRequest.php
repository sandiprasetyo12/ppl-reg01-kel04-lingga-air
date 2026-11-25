<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PengumumanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
       return $this->user()?->isBpbd() ?? false;
    }

    public function rules(): array
    {
        return [
            'judu' => ['required', 'string', 'max:150'],
            'isi' => ['required', 'string'],
            'tingkat_kelayakan' => [
                'required',
                Rule::in(['aman', 'waspada', 'kritis']),
            ],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
