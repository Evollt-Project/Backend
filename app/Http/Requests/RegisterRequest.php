<?php

namespace App\Http\Requests;

class RegisterRequest extends BaseRequest
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
            'first_name' => 'required|max:40',
            'surname' => 'max:40',
            'last_name' => 'max:40',
            'email' => 'required|email|unique:users,email',
            'phone' => 'max:11|unique:users,phone',
            'password' => 'required|min:8'
        ];
    }
}
