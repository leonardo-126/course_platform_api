<?php

namespace App\Http\Requests\courseAuthor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseAuthorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $course = $this->route('course');
        return $course->authorEntries()
            ->where('user_id', $this->user()->id)
            ->where('is_owner', true)
            ->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'can_view_student_progress' => ['required', 'boolean'],
        ];
    }
}
