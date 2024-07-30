<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DailyReportsRequests extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
        'date' => 'required|date',
        'period_time' => 'required|string',
        'app_crc' => 'required|integer',
        'loan_crc' => 'required|integer',
        'app_plxs' => 'required|integer',
        'loan_plxs' => 'required|integer',
        'amount_plxs' => 'required|numeric',
        'amount_banca' => 'required|numeric',
        'loan_ctbs' => 'required|integer',
        'conver_banca' => 'required|numeric',
        'conver_ctbs' => 'required|numeric',
        ];
    }
}
