<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientValidator extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('patient') ? $this->route('patient')->id : null;

        return [
            'name' => 'required|string|max:255',
            'national_id' => 'required|string|max:255|unique:patients,national_id,'.$id,
            'city' => 'required|string|max:255',
            'details' => 'nullable|string',
            'phone1' => 'nullable|string|max:255',
            'phone2' => 'nullable|string|max:255',
            'disease_type' => 'nullable|string|max:255',
            'medical_rec' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required',
            'national_id.required' => 'The national ID field is required',
            'national_id.unique' => 'The national ID has already been taken',
            'city.required' => 'The city field is required',
        ];
    }
}
