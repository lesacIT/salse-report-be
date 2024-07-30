<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BusinessGoalRequest extends FormRequest
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
                'date'=> ['required', 'date'],
                'crc_app'=> ['required', 'string'],
                'crc_loan'=> ['required', 'string'],
                'plxs_app'=> ['required', 'string'],
                'plxs_loan'=> ['required', 'string'],
                'amount_plxs'=> ['required', 'string'],
                'loan_ctbs'=> ['required', 'string'],
                'convert_banca'=> ['required', 'string'],
                "amount_banca" =>['required', 'string'],
                'convert_banca'=>['required', 'string'],
                "convert_ctbs" =>['required', 'string'],
        ];
    }
}
