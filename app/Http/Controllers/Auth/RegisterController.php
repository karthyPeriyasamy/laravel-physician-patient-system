<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Specialists;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }
    /**
    * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    */
    public function showUserRegisterForm()
    {
        return view('auth.register', ['url' => 'user']);
    }

    /**
    * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    */
    public function showPhysicianRegisterForm()
    {
        $specialists = Specialists::where('status', 1)
                                ->orderBy('name', 'asc')
                                ->get();
        return view('auth.register', ['url' => 'physician', 'specialists' => $specialists]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  Request  $request
     * @return \App\Models\User
     */
    protected function createUser(Request $request)
    {
        $this->validator($request->all())->validate();
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'password' => Hash::make($request->password),
        ]);
        return redirect()->intended('login');
    }
    /**
     * Handle the admin registration request
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function createPhysician(Request $request)
    {
        $this->validator($request->all())->validate();
        $specialist = $request->specialist ? implode(',', $request->specialist): null;

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'specialist' => $specialist,
            'address' => $request->address,
            'is_physician' => 1,
            'password' => Hash::make($request->password),
        ]);
        return redirect()->intended('login');
    }
}
