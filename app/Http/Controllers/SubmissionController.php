<?php

namespace App\Http\Controllers;

use App\Helpers\APIHelper;
use App\Http\Requests\StoreSubmissionRequest;
use App\Http\Resources\SubmissionResource;
use App\Models\Submission;
use App\Notifications\ApplicationApprovedOrRejectedNotification;
use App\Notifications\NewSubmissionNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $submissions = Submission::query()->with(['jobListing', 'user'])
        ->whereHas('jobListing', function ($query) {
            $query->where('user_id', auth()->id());
        })->orderByDesc('created_at');

        if ($request->status)
            $submissions->where('status', $request->status);

        $submissions = $submissions->get();

        return APIHelper::success(null, SubmissionResource::collection($submissions));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubmissionRequest $request)
    {
        $data = $request->validated();

        $data['user_id'] = auth()->id();

        // check if the user has already applied for this job
        $existingSubmission = Submission::where('user_id', $data['user_id'])
        ->where('job_listing_id', $data['job_listing_id'])
        ->first();

        if ($existingSubmission) {
            throw new Exception("You have already applied for this job");
        }

        if ($request->hasFile('resume')) {
            $data['resume'] = config('app.url') . Storage::url(
                $request->file('resume')->store('public/resumes')
            );
        }

        if ($request->hasFile('cover_letter')) {
            $data['cover_letter'] = config('app.url') . Storage::url(
                $request->file('cover_letter')->store('public/cover_letters')
            );
        }

        $submission = Submission::create($data);

        // send an email to employer after 10 minutes
        $employer = $submission->jobListing->user;
        $employer->notify(new NewSubmissionNotification($submission));

        return APIHelper::success("Application submitted successfully", new SubmissionResource($submission));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Submission $submission)
    {
        $data = $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        if ($submission->status != 'pending') {
            throw new Exception("You can only update the status of a pending submission");
        }

        $submission->update($data);
        $submission = $submission->fresh();

        // send an email to the applicant
        $submission->user->notify(
            new ApplicationApprovedOrRejectedNotification(
                $submission->status,
                $submission->jobListing->title
            )
        );

        return APIHelper::success(
            "Submission updated successfully",
            new SubmissionResource($submission)
        );
    }
}
