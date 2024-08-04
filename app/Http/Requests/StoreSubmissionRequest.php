<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubmissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'job_listing_id' => ['required', 'integer', 'exists:job_listings,id'],
            'resume' => ['required', 'file', 'mimes:pdf,doc,docx'],
            'cover_letter' => ['nullable', 'file', 'mimes:pdf,doc,docx'],
        ];
    }
}
