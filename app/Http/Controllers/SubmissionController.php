<?php

namespace App\Http\Controllers;

use App\Helpers\APIHelper;
use App\Http\Requests\StoreSubmissionRequest;
use App\Models\Submission;
use App\Notifications\NewSubmissionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubmissionRequest $request)
    {
        $data = $request->validated();

        $data['user_id'] = auth()->id();

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

        return APIHelper::success("Application submitted successfully", $submission);
    }

    /**
     * Display the specified resource.
     */
    public function show(Submission $submission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Submission $submission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Submission $submission)
    {
        //
    }
}
