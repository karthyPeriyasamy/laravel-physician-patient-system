<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Specialists;
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
        $specialists = Specialists::where('status', 1)
                ->orderBy('name', 'asc')
                ->get();
        return view('home', ['specialists' => $specialists]);
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
