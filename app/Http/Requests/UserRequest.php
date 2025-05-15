<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidCPF;

class UserRequest extends FormRequest
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
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required'],
            'cpf' => ['required', 'string', new ValidCPF(), 'unique:users,cpf'],
            'name' => ['required', 'string']
        ];
    }

    // public function messages(): array
    // {
    //     return [
    //         'email' => 'Dados de login inválidos',
    //         'password' => 'Dados de login inválidos',
    //     ];
    // }
}
