<?php

namespace Database\Seeders;

use App\Models\JobListing;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        if (!User::where('email', 'test@user.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@user.com',
                'password' => bcrypt('password'),
                'is_employer' => false,
            ]);
        }

        if (!($employer = User::where('email', 'test@employer.com')->first())) {
            $employer = User::factory()->create([
                'name' => 'Test Employer',
                'email' => 'test@employer.com',
                'password' => bcrypt('password'),
                'is_employer' => true,
            ]);
        }
        JobListing::factory(5)->create([
            'user_id' => $employer->id,
        ]);
    }
}
