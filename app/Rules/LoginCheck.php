<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\UserModel;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginCheck implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

    protected $request;

    public function __construct($Request)
    {
        $this->request = $Request;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $email = $this->request->input('email');
        $password = $this->request->input('password');
        $loginStatus = false;

        $chekemail = UserModel::where('email', $email)->count();
        if ($chekemail > 0) {
            $adminPassword = UserModel::where('email', $email)->value('password');
            if (Hash::check($password, $adminPassword)) {
                $loginStatus = true;
            }
        }

        if ($loginStatus) {
            $ambilUser = UserModel::where('email', $email)->first();
            session::put('loginStatus', true);
            session::put('ambilUser', $ambilUser);
        } else {
            $fail("Email dan Password Salah!");
        }
    }
}
