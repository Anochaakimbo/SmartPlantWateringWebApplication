<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class PumpControlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('pump_control')->insert([
            'pump_state' => false,
            'manual_control' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
