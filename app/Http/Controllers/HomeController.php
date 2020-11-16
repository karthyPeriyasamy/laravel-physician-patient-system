<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appointments;

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
     * Show the user dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $appointments = Appointments::where('status', 1)->orderBy('created_at', 'desc')->get();
        return view('home', ['appointments' => $appointments]);
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

    /**
     * Show the physician dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function physicianHome()
    {
        $appointments = Appointments::where('status', 1)->orderBy('created_at', 'desc')->get();
        return view('physician-home', [ 'appointments' => $appointments]);
    }
}
