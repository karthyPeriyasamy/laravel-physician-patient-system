<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Specialists;

class SpecialistsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $specialists = [
            [
               'name'=>'Cardiologist',

            ],
            [
               'name'=>'Audiologist',
            ],
            [
                'name'=>'Paediatrician',
             ],
             [
                'name'=>'Gynaecologist',
             ],
             [
                'name'=>'Psychiatrists',
             ],
        ];

        foreach ($specialists as $key => $value) {
            Specialists::create($value);
        }
    }
}
