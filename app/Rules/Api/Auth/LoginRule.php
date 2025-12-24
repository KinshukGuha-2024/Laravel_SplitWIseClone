<?php

namespace App\Rules\Api\Auth;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRule implements ValidationRule
{
    public $email, $callback, $password;
    
    public function __construct(callable $callback, $email, $password)
    {
        $this->callback = $callback;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!auth()->attempt(['email' => $this->email, 'password' => $this->password], true)) {
            throw new HttpResponseException(response(["message" => 'Provided Credentials Doesnt Match, Please Try Again!!'], 403));
        } else {
            call_user_func($this->callback, auth()->user());
        }
    }
}
