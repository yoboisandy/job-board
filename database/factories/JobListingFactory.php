<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobListing>
 */
class JobListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userId = User::factory()->create([
            'is_employer' => true,
        ])->id;
        return [
            'user_id' => $userId,
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'company' => $this->faker->company,
            'location' => $this->faker->city,
            'instructions' => $this->faker->paragraph,
        ];
    }
}
