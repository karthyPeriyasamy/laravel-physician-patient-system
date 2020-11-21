<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\API\BaseController;

class LoginController extends BaseController
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), Config::get('constants.api.error.login.validation'));
        }
        if (auth()->attempt(['email'=>$request->email, 'password'=>$request->password])) {
            $user = auth()->user();
            if ($user->approved === 0) {
                return $this->errorResponse([], Config::get('constants.api.error.login.approved'));
            }

            $adminRole = $user->is_admin;
            $physicianRole = $user->is_physician;
            if ($adminRole) {
                $this->scope = 'admin';
            } elseif ($physicianRole) {
                $this->scope = 'physician';
            } else {
                $this->scope = 'user';
            }
            $token = $user->createToken(Config::get('constants.api.secret'), [$this->scope]);
            return $this->successResponse($token, Config::get('constants.api.success.login.valid'));
        }
    }
}
