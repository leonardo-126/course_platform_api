<?php

namespace App\Http\Requests\courseAuthor;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseAuthorRequest extends FormRequest
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
            'user_id'                   => ['required', 'integer', 'exists:users,id'],
            'can_view_student_progress' => ['sometimes', 'boolean'],
        ];
    }
}
