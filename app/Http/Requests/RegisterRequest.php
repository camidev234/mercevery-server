<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'email' => 'string|required|unique:users',
            'password' => 'string|required|min:8|max:25',
            'phone_number' => 'string|required|min:9|max:11',
            'document_type' => 'string|required',
            'address' => 'string|required|max:55',
            'role_id' => 'integer|exists:roles,id|required'
        ];
    }
}
