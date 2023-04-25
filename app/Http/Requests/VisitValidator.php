<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VisitValidator extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'treatment_plan' => 'required',
            'type' => 'required',
            'units_number' => 'required|numeric|min:1',
            'cost' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'paid' => 'required|numeric|min:0',
            'remaining' => 'required|numeric|min:0',
            'note' => 'nullable',
            'date' => 'required|date',
        ];
    }
}
