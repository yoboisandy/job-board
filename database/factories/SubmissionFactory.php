<?php

namespace Database\Factories;

use App\Models\JobListing;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Submission>
 */
class SubmissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jobListing = JobListing::factory()->create();
        $user = User::factory()->create();
        return [
            'job_listing_id' => $jobListing->id,
            'user_id' => $user->id,
            'resume' => $this->faker->url . '.pdf',
            'cover_letter' => $this->faker->url . '.pdf',
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
        ];
    }
}
