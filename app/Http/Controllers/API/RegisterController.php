<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use App\Models\User;
use App\Models\Specialists;
use App\Http\Controllers\API\BaseController;

class RegisterController extends BaseController
{
    /**
      * Get a validator for an incoming user registration request.
      *
      * @param  array  $data
      * @return \Illuminate\Contracts\Validation\Validator
      */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            // required|array
        ]);
    }
    /**
      * Get a validator for an incoming physician registration request.
      *
      * @param  array  $data
      * @return \Illuminate\Contracts\Validation\Validator
      */
    protected function physicianValidator(array $data)
    {
        return Validator::make($data, [
              'name' => ['required', 'string', 'max:255'],
              'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
              'password' => ['required', 'string', 'min:8'],
              'specialists' => [ 'required', 'array']
          ]);
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  Request  $request
     * @return Response
     */
    protected function createUser(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return $this->errorResponse(Config::get('constants.api.error.register.validation'), $validator->errors(), 422);
        }
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
                'password' => Hash::make($request->password),
            ]);
            $success['token'] =  $user->createToken(Config::get('constants.api.secret'), ['user']);
            $success['name'] =  $user->name;
            return $this->successResponse($success, Config::get('constants.api.success.register.create'));
        } catch (\Exception $error) {
            return $this->exceptionResponse(Config::get('constants.api.exception'), $error->getMessage());
        }
    }

    /**
    * Create a new physician user instance after a valid registration.
    *
    * @param  Request  $request
    * @return Response
    */
    protected function createPhysician(Request $request)
    {
        $validator = $this->physicianValidator($request->all());
        if ($validator->fails()) {
            return $this->errorResponse(Config::get('constants.api.error.register.validation'), $validator->errors(), 422);
        }
        $specialists = Specialists::whereIn('id', $request->specialists);
        if ($specialists->count() !== count($request->specialists)) {
            return $this->errorResponse(Config::get('constants.api.error.register.validation'), $specialists, 422);
        }
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
                'is_physician' => 1,
                'password' => Hash::make($request->password),
            ]);
            $user->specialists()->attach($request->specialists);
            $success['token'] =  $user->createToken(Config::get('constants.api.secret'), ['physician']);
            $success['name'] =  $user->name;
            return $this->successResponse($success, Config::get('constants.api.success.register.create'));
        } catch (\Exception $error) {
            return $this->exceptionResponse(Config::get('constants.api.exception'), $error->getMessage());
        }
    }
}
