<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CreateAppointmentSeeder::class,
            CreateUsersSeeder::class,
            SpecialistsSeeder::class,
            AppointmentStatusSeeder::class
        ]);
    }
}
