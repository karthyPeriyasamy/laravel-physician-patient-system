<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointments;

class AppointmentsController extends Controller
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
    * Create a new appointment.
    *
    * @param  array  $data
    * @return \App\Models\Appointments
    */
    protected function create(array $data)
    {
        return Appointments::create([
            'user_id' => $data['user_id'],
            'specialist' => $data['specialist'],
            'description' => $data['description'],
        ]);
    }
}
