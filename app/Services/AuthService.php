<?php

namespace App\Services;

use App\Helpers\ServiceResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class AuthService extends BaseService
{
    public function login(array $data): ServiceResponse
    {
        $validator = Validator::make($data, $this->loginRules(), $this->messages());

        if ($validator->fails()) return $this->setResponse(406, $validator->errors(), null);

        $email = $data['email'];
        $password = $data['password'];

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $token = Auth::user()->createToken( env('APP_NAME') )->plainTextToken;

            return $this->setResponse(200, 'Success', ['user' => Auth::user(), 'token' => $token]);
        }

        return $this->setResponse(401, 'Email or password is wrong !', null);
    }

    public function logOut(): ServiceResponse
    {
        try {
            Auth::user()->tokens()->delete();;

            return $this->setResponse(200, '', null);
        } catch (\Throwable $e) {
            return $this->setResponse(500, $e->getMessage(), null);
        }
    }

    private function loginRules()
    {
        return [
            'email' => 'required|max:100|email:rfc',
            'password' => 'required|max:8|min:1',
        ];
    }
}
