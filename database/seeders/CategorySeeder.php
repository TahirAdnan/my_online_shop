<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    //  Users data feeding
        // \App\Models\User::factory(20)->create([
        //     'name' => 'Test User',
        //     'email' => 'test@test.com',
        // ]);

    //  Category data feeding        
        \App\Models\Category::factory()->count(10)->create();
    }
}
