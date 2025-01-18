<?php

namespace App\Http\Requests;

use App\Models\PendingCommunityRequests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Contracts\Validation\Validator as Valid; 
use Illuminate\Http\Exceptions\HttpResponseException;

class ApplySubredditRequest extends FormRequest
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
            'community_id' => 'required|exists:communities,id',
            'user_id' => 'required|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'community_id.required' => 'Please select a subreddit to apply!',
            'community_id.exists' => 'Unable to find this subreddit',
            'user_id.required' => 'Please login to apply for this subreddit!',
            'user_id.exists' => 'Unable to find this user',
            'user_id.already_applied' => 'You have already applied to this subreddit!',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $existing_requests = PendingCommunityRequests::where('user_id', $this->user_id)
                ->where('community_id', $this->community_id)
                ->exists();

            if ($existing_requests) {
                $validator->errors()->add('user_id', $this->messages()['user_id.already_applied']);
            }
        });
    }

    protected function failedValidation(Valid $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], 422));
    }
}
