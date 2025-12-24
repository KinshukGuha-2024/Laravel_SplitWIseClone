<?php

namespace App\Http\Requests\Api;

use App\Models\User;
use App\Rules\Api\Auth\LoginRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        return $this->merge(['invalid' => 'validation']);
    }
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
            "password.required" => 'Please provide your account password!',
            "password.min" => "Please provide a minimum 8 characters long password!",
            "password.regex" => "Password must include at least one lowercase letter, one uppercase letter, one number!",
        ];
    }

    public function withValidator($validator) {
        if(!$validator->fails()) {
            $valid = validator()->make($this->all(), ['invalid' => ['required', new LoginRule([$this, 'setUser'], $this->email, $this->password)]]);
            if ($valid->fails()) {
                $validator->errors()->merge($valid->errors());
            }
        }
    }

    public function setUser(User $user)
    {
        $this->user = $user;
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
