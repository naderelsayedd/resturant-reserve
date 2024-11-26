<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('meals')->insert([
            [
                'price' => 1500,
                'description' => 'Grilled Chicken with Rice',
                'quantity_available' => 10,
                'discount' => '10%',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'price' => 1200,
                'description' => 'Vegetarian Pasta',
                'quantity_available' => 15,
                'discount' => '5%',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'price' => 2000,
                'description' => 'Steak with Mashed Potatoes',
                'quantity_available' => 8,
                'discount' => '15%',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'price' => 800,
                'description' => 'Classic Caesar Salad',
                'quantity_available' => 20,
                'discount' => '0%',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'price' => 1000,
                'description' => 'Spaghetti Bolognese',
                'quantity_available' => 12,
                'discount' => '10%',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
