<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|size:11|unique:users',
            'image' => 'required|file|max:2048',
            'is_admin' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name field must be a string.',
            'name.max' => 'The name field may not be greater than :max characters.',
            'email.required' => 'The email field is required.',
            'email.string' => 'The email field must be a string.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'The email field may not be greater than :max characters.',
            'email.unique' => 'The email address is already taken.',
            'password.required' => 'The password field is required.',
            'password.string' => 'The password field must be a string.',
            'password.min' => 'The password must be at least :min characters.',
            'phone.required' => 'The phone field is required.',
            'phone.string' => 'The phone field must be a string.',
            'phone.size' => 'The phone field must be :size characters.',
            'phone.unique' => 'The phone number is already taken.',
            'image.required' => 'The image field is required.',
            'image.file' => 'The image must be a valid image file.',
            'image.max' => 'The image may not be greater than :max kilobytes.',
            'is_admin.required' => 'The is_admin field is required.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Validation Error',
            'errors' => $validator->errors(),
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}