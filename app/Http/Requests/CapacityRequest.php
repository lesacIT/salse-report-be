<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CapacityRequest extends FormRequest
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
            //
            'range_of_vehicle_id'=>'required|integer|exists:range_of_vehicle,id',
            'name'=>'required|string',
            'money'=>'required|integer',
            'quantity'=>'required|integer'
        ];
    }
}
