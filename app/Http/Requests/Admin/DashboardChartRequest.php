<?php 

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class DashboardChartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isBpbd() ?? false;
    }

    public function rules(): array
    {
        return [
            'start_date' => ['sometimes', 'nullable', 'date_format:Y-m-d'],
            'end_date' => ['sometimes', 'nullable', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            'kecamatan' => ['sometimes', 'nullable', 'string', 'max:100', 'exists:desas,kecamatan'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void{
                if ($validator->errors()->hasAny(['start_date', 'end_date'])) {
                    return;
                }

                $startDate = $this->input('start_date');
                $endDate = $this->input('end_date');

                if ($startDate && $endDate && $startDate > $endDate) {
                    $validator->errors()->add('end_date', 'Tanggal akhir harus sama dengan atau setelah tanggal mulai.');
                }
            },
        ];
    }
}
