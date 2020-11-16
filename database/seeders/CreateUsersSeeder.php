<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class CreateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            [
               'name'=>'Admin',
               'email'=>'admin@test.com',
               'address' => 'Coimbatore',
               'is_admin'=> 1,
               'approved'=> 1,
               'password'=> bcrypt('12345678'),
            ]
        ];

        foreach ($user as $value) {
            User::create($value);
        }
    }
}
