<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommunityStoreRequest extends FormRequest
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
            'name' => 'required|unique:communities,name',
            'description' => 'required',
            'type' => 'required',
        ];
    }

    public function messages() {
        return [
            'name.required' => 'Subreddit name is required.',
            'name.unique' => 'This subreddit name already exists.',
            'description.required' => 'Description is required.',
            'type.required' => 'Please specify the subreddit type',
        ];
    }
}
