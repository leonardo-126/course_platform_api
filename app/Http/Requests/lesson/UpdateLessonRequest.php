<?php

namespace App\Http\Requests\lesson;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLessonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('manageContent', $this->route('course'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'            => ['sometimes', 'string', 'max:255'],
            'type'             => ['sometimes', Rule::in(['video', 'text', 'quiz'])],
            'content'          => ['sometimes', 'nullable', 'string'],
            'video_url'        => ['sometimes', 'nullable', 'url', 'max:500'],
            'duration_minutes' => ['sometimes', 'integer', 'min:0'],
            'sort_order'       => ['sometimes', 'integer', 'min:0'],
            'is_preview'       => ['sometimes', 'boolean'],
        ];
    }
}
