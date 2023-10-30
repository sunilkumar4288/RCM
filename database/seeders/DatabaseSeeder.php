<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
      
        \App\Models\User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Head',
            'email' => 'head@admin.com',
            
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Manager',
            'email' => 'manager@admin.com',
            
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Team Lead',
            'email' => 'tl@admin.com',
            
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Executive',
            'email' => 'executive@admin.com',
           
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Guest',
            'email' => 'guest@admin.com',
           
        ]);


        //Guest
        \App\Models\User::factory(4)->create();
       

    }
}
