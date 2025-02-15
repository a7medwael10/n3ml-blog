<?php

namespace App\Http\Requests\Website;

use Illuminate\Foundation\Http\FormRequest;

class CreatePost extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'tags' => ['required', 'array'],
            'tags.*' => ['exists:tags,id'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'published_at' => ['nullable',  'after_or_equal:now'],
        ];
    }

    protected function passedValidation()
    {
        // set scheduled at to now() if null
        if (is_null($this->published_at)) {
            $this->merge(['published_at' => now()]);
        }
    }
}
