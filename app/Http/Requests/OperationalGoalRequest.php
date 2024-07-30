<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OperationalGoalRequest extends FormRequest
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
            'date' => ['required', 'date'],            
            'time_slot_id'=> ['required', 'integer'],
            'daily_activity_id'=> ['required', 'integer'],
            'place'=> ['required', 'string'],
            'detail'=> ['required', 'string'],
            'time_slot_id2'=> ['required', 'integer'],
            'daily_activity_id2'=> ['required', 'integer'],
            'place2'=> ['required', 'string'],
            'detail2'=> ['required', 'string'],
            'time_slot_id3'=> ['required', 'integer'],
            'daily_activity_id3'=> ['required', 'integer'],
            'place3'=> ['required', 'string'],
            'detail3'=> ['required', 'string'],
            'time_slot_id4'=> ['required', 'integer'],
            'daily_activity_id4'=> ['required', 'integer'],
            'place4'=> ['required', 'string'],
            'detail4'=> ['required', 'string'],
        ];
    }
}
