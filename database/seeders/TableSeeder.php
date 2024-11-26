<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tables')->insert([
            [
                'capacity' => 2,
            ],
            [
                'capacity' => 4,
            ],
            [
                'capacity' => 6,
            ],
            [
                'capacity' => 8,
            ],
            [
                'capacity' => 10,
            ],
            [
                'capacity' => 4,
            ],
            [
                'capacity' => 6,
            ],
            [
                'capacity' => 2,
            ],
            [
                'capacity' => 10,
            ],
            [
                'capacity' => 4,
            ],
        ]);
    }
}
