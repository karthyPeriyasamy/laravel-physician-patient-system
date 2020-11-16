<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::user()) {
            if (Auth::user()->is_admin) {
                $this->adminHome();
            } elseif (Auth::user()->is_physician && Auth::user()->approved) {
                $this->physicianHome();
            } elseif (Auth::user()->approved) {
                return view('home');
            } else {
                Auth::logout();
                return redirect()->route('/');
            }
        } else {
            return redirect()->route('login');
        }
    }
    /**
    * Show the Admin dashboard.
    *
    * @return \Illuminate\Contracts\Support\Renderable
    */
    public function adminHome()
    {
        $users = User::where('is_admin', 0)
        ->orderBy('created_at', 'desc')
        ->get();
        return view('admin-home', [ 'users' => $users]);
    }

    public function physicianHome()
    {
        $users = User::where('is_admin', 0)
        ->orderBy('created_at', 'desc')
        ->get();
        return view('physician-home', [ 'users' => $users]);
    }
}
