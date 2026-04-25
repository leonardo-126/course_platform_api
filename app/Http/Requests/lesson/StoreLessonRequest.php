<?php

namespace App\Http\Requests\lesson;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLessonRequest extends FormRequest
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
            'title'            => ['required', 'string', 'max:255'],
            'type'             => ['required', Rule::in(['video', 'text', 'quiz'])],
            'content'          => ['nullable', 'string', 'required_if:type,text'],
            'video_url'        => ['nullable', 'url', 'max:500', 'required_if:type,video'],
            'duration_minutes' => ['nullable', 'integer', 'min:0'],
            'sort_order'       => ['nullable', 'integer', 'min:0'],
            'is_preview'       => ['nullable', 'boolean'],
        ];
    }
}
