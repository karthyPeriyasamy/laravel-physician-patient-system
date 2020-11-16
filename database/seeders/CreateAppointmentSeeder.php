<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointments;

class CreateAppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $appointments = [
            [
               'user_id'=>1,
               'specialist'=>1,
               'description' => 'Health checkup 1 time',
               'status'=> 1,
            ],
            [
                'user_id'=>1,
                'specialist'=>2,
                'description' => 'Health checkup 2 time',
                'status'=> 1,
            ],
             [
                'user_id'=>1,
                'specialist'=>1,
                'description' => 'Health checkup 3 time',
                'status'=> 1,
             ]
        ];

        foreach ($appointments as $value) {
            Appointments::create($value);
        }
    }
}
