<?php

namespace App\Http\Requests\course;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('course'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        $courseId = $this->route('course')->id;

        return [
            'title'             => ['sometimes', 'string', 'max:255'],
            'slug'              => ['sometimes', 'string', 'max:255', "unique:courses,slug,{$courseId}"],
            'description'       => ['sometimes', 'nullable', 'string'],
            'thumbnail'         => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'], // 2MB
            'estimated_minutes' => ['sometimes', 'integer', 'min:0'],
            'xp_reward'         => ['sometimes', 'integer', 'min:0'],
            'status'            => ['sometimes', 'in:draft,published,archived'],
        ];
    }
}
