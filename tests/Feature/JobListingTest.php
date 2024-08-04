<?php

namespace Tests\Feature;

use App\Models\JobListing;
use App\Models\User;
use Tests\TestCase;

class JobListingTest extends TestCase
{
    public function test_a_employer_can_create_a_job_listing()
    {
        $user = User::factory()->create([
            'is_employer' => true,
        ]);
        $this->actingAs(User::find($user->id));

        $jobListing = JobListing::factory()->make();

        $response = $this->postJson('/api/jobs', $jobListing->toArray());

        $response->assertOk();

        $this->assertDatabaseHas('job_listings', [
            'title' => $jobListing->title,
            'description' => $jobListing->description,
            'company' => $jobListing->company,
            'location' => $jobListing->location,
            'instructions' => $jobListing->instructions,
        ]);
    }

    public function test_a_job_seeker_cannot_create_a_job_listing()
    {
        $user = User::factory()->create([
            'is_employer' => false,
        ]);
        $this->actingAs(User::find($user->id));

        $jobListing = JobListing::factory()->make();

        $response = $this->postJson('/api/jobs', $jobListing->toArray());

        $response->assertStatus(403);
    }

    public function test_only_employer_who_created_job_can_delete_job_listing()
    {
        $employer = User::factory()->create([
            'is_employer' => true,
        ]);
        $jobListing = JobListing::factory()->create([
            'user_id' => $employer->id,
        ]);

        $employer2 = User::factory()->create([
            'is_employer' => true,
        ]);
        $this->actingAs(User::find($employer2->id));

        $response = $this->deleteJson("/api/jobs/{$jobListing->id}");

        $response->assertStatus(403);
    }

    public function test_a_job_seeker_can_search_for_jobs()
    {
        $jobListing = JobListing::factory()->create();

        $response = $this->getJson("/api/jobs?q={$jobListing->title}");

        $response->assertOk();
        $response->assertJsonFragment([
            'title' => $jobListing->title,
            'description' => $jobListing->description,
            'company' => $jobListing->company,
            'location' => $jobListing->location,
            'instructions' => $jobListing->instructions,
        ]);
    }
}
