<?php

namespace Tests\Feature;

use App\Models\JobListing;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SubmissionTest extends TestCase
{
    public function test_a_job_seeker_can_submit_a_job_application()
    {
        $user = User::factory()->create([
            'is_employer' => false,
        ]);
        $jobListing = JobListing::factory()->create();

        $jobApplication = Submission::factory()->make([
            'job_listing_id' => $jobListing->id,
            'resume' => UploadedFile::fake()->create('resume.pdf'),
            'cover_letter' => UploadedFile::fake()->create('cover_letter.pdf'),
        ]);

        $this->actingAs(User::find($user->id));

        $response = $this->postJson("/api/submissions", $jobApplication->toArray());

        $response->assertOk();

        $submission = Submission::where('user_id', $user->id)->first();

        $this->assertDatabaseCount('submissions', 1);

        $this->assertEquals($submission->resume, $response->json('data.resume'));

        $this->assertEquals($submission->cover_letter, $response->json('data.cover_letter'));
    }

    public function test_a_job_seeker_can_only_submit_a_job_application_once()
    {
        $user = User::factory()->create([
            'is_employer' => false,
        ]);
        $jobListing = JobListing::factory()->create();

        $jobApplication = Submission::factory()->make([
            'job_listing_id' => $jobListing->id,
            'resume' => UploadedFile::fake()->create('resume.pdf'),
            'cover_letter' => UploadedFile::fake()->create('cover_letter.pdf'),
        ]);

        $this->actingAs(User::find($user->id));

        $this->postJson("/api/submissions", $jobApplication->toArray());

        $response = $this->postJson("/api/submissions", $jobApplication->toArray());

        $response->assertStatus(500);
    }

    public function test_employer_can_view_all_submissions_for_their_job_listing()
    {
        $employer = User::factory()->create([
            'is_employer' => true,
        ]);
        $jobListing = JobListing::factory()->create([
            'user_id' => $employer->id,
        ]);

        $submissions = Submission::factory()->count(5)->create([
            'job_listing_id' => $jobListing->id,
        ]);

        $this->actingAs(User::find($employer->id));

        $response = $this->getJson("/api/submissions");

        $response->assertOk();

        $this->assertEquals($submissions->pluck('id'), collect($response->json('data'))->pluck('id'));
    }

    public function test_a_employer_can_accept_or_reject_a_job_application()
    {
        $employer = User::factory()->create([
            'is_employer' => true,
        ]);
        $jobListing = JobListing::factory()->create([
            'user_id' => $employer->id,
        ]);

        $submission = Submission::factory()->create([
            'job_listing_id' => $jobListing->id,
            'status' => 'pending',
        ]);

        $this->actingAs(User::find($employer->id));

        $response = $this->putJson("/api/submissions/{$submission->id}", [
            'status' => 'approved',
        ]);

        $response->assertOk();

        $this->assertEquals('approved', $response->json('data.status'));
    }
}
