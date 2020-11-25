<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AppointmentStatus;

class AppointmentStatusSeeder extends Seeder
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
               'name' => 'open'
            ],
            [
                'name' => 'progress'
            ],
            [
                'name' => 'close'
            ]
        ];

        foreach ($appointments as $value) {
            AppointmentStatus::create($value);
        }
    }
}
