<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddUserToCommunityRequest extends FormRequest
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
            'subreddit_id' => 'required|exists:communities,id',
            'user_id' => 'required',
            'user_ids.*' => 'exists:users,id',
            'is_accepted' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'subreddit_id.required' => 'Please choose a subreddit to proceed.',
            'subreddit_id.exists' => 'The selected subreddit does not exist.',
            'user_id.required' => 'You need to select at least one user to add.',
            'user_id.exists' => 'This person is already a part of this subreddit!',
        ];
    }
}
