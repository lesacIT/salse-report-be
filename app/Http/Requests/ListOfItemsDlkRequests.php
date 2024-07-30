<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListOfItemsDlkRequests extends FormRequest
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
            'date'=>'required|date',
            'name_dlk'=>'required|string',
            'local_province_id'=>'required|integer|exists:local_province,id',
            'local_ward_id'=>'required|integer|exists:local_ward,id',
            'local_district_id'=>'required|integer|exists:local_district,id',
            'address_dlk'=>'required|string',
            'list_of_types_dlk_id'=>'required|integer|exists:list_of_types_dlk,id',
             'full_name_of_representative'=>'required|string',
            'list_of_items_dlk_id'=>'required|integer|exists:list_of_items_dlk,id',
            'images' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'locate'=>'required|string',
            'status_dlk'=>'required|integer',
            'advise_crc'=>'required|integer',
            'eligible_crc'=>'required|integer',
            'go_to_app_crc'=>'required|integer',
            'loan_crc'=>'required|integer',
        ];
    }
}
