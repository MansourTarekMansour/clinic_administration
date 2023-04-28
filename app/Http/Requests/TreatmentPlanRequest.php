<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TreatmentPlanRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'medical_procedures' => 'required|array',
            'medical_procedures.*.name' => 'required|string|max:255',
            'medical_procedures.*.types' => 'required|array',
            'medical_procedures.*.types.*.name' => 'required|string|max:255',
            'medical_procedures.*.types.*.price' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name field must be a string.',
            'name.max' => 'The name field may not be greater than :max characters.',
            'medical_procedures.required' => 'The medical procedures field is required.',
            'medical_procedures.array' => 'The medical procedures field must be an array.',
            'medical_procedures.*.name.required' => 'The medical procedure name field is required.',
            'medical_procedures.*.name.string' => 'The medical procedure name field must be a string.',
            'medical_procedures.*.name.max' => 'The medical procedure name field may not be greater than :max characters.',
            'medical_procedures.*.types.required' => 'The types field for medical procedure :input is required.',
            'medical_procedures.*.types.array' => 'The types field for medical procedure :input must be an array.',
            'medical_procedures.*.types.*.name.required' => 'The type name field for medical procedure :input is required.',
            'medical_procedures.*.types.*.name.string' => 'The type name field for medical procedure :input must be a string.',
            'medical_procedures.*.types.*.name.max' => 'The type name field for medical procedure :input may not be greater than :max characters.',
            'medical_procedures.*.types.*.price.required' => 'The type price field for medical procedure :input is required.',
            'medical_procedures.*.types.*.price.numeric' => 'The type price field for medical procedure :input must be numeric.',
            'medical_procedures.*.types.*.price.min' => 'The type price field for medical procedure :input must be at least :min.',
        ];
    }
}
