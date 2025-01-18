<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JoinSubredditRequest extends FormRequest
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
            'subreddit_id' => 'required',
            'user_id' => 'required|unique:community_users,user_id'
        ];
    }

    public function messages(): array {
        return [
            'subreddit_id.required' => 'Please select a public subreddit for joining!',
            'user_id.required' => 'User id is required for joining!',
            'user_id.unique' => 'You have already joined the subreddit!',
        ];
    }
}
