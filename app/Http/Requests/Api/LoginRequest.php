<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
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
            'email' => 'required|email:strict',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            "email.required" => 'Please provide an email address!',
            "email.email" => 'please provide an valid email address!',
            "password" => 'Please provide your account password!'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response(
            [
                'message'   => 'Validation errors',
                'errors'    => $this->messageBagArr($validator)
            ],
            422
        ));
    }
    private function messageBagArr($validator)
    {
        $errors = [];
        $messageBag = $validator->errors();
        foreach ($messageBag->keys() as $fieldKey) {
            $errors[str_replace('.', '_', $fieldKey)] = $messageBag->first($fieldKey);
        }
        return $errors;
    }
}
