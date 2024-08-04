<?php

namespace App\Http\Controllers;

use App\Helpers\APIHelper;
use App\Http\Requests\StoreJobListingRequest;
use App\Http\Requests\UpdateJobListingRequest;
use App\Models\JobListing;
use Illuminate\Http\Request;

class JobListingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function getEmployerJobs($employerId)
    {
        if (auth()->id() != $employerId) {
            return APIHelper::error("You are not authorized to view this resource", null, 403);
        }

        $jobs = JobListing::where('user_id', $employerId)->orderByDesc('created_at')->get();

        return APIHelper::success(null, $jobs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJobListingRequest $request)
    {
        $data = $request->validated();

        $data['user_id'] = auth()->id();

        $jobListing = JobListing::create($data);

        return APIHelper::success("Job created successfully", $jobListing);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJobListingRequest $request, JobListing $jobListing)
    {
        $data = $request->validated();

        $jobListing->update($data);

        return APIHelper::success("Job updated successfully", $jobListing->fresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobListing $jobListing)
    {
        $jobListing->delete();

        return APIHelper::success("Job deleted successfully");
    }
}
