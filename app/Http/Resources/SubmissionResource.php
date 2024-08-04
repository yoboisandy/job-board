<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubmissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "resume" => $this->resume,
            "cover_letter" => $this->cover_letter,
            "status" => $this->status,
            "job_listing" => [
                "id" => $this->jobListing->id,
                "title" => $this->jobListing->title,
                "company" => $this->jobListing->company,
                "location" => $this->jobListing->location,
                "description" => $this->jobListing->description,
                "instructions" => $this->jobListing->instructions,
                "created_at" => $this->jobListing->created_at,
            ],
            "user" => [
                "id" => $this->user->id,
                "name" => $this->user->name,
                "email" => $this->user->email,
                "created_at" => $this->user->created_at,
            ],
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
