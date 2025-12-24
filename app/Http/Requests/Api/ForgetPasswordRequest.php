<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ForgetPasswordRequest extends FormRequest
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
            'email' => 'required|email:strict|exists:users,email',
        ];
    }

     public function messages()
    {
        return [
            "email.required" => 'Please provide an email address!',
            "email.exists" => 'This email address cannot be found!',
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
