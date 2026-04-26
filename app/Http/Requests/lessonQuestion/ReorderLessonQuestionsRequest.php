<?php

namespace App\Http\Requests\lessonQuestion;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReorderLessonQuestionsRequest extends FormRequest
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
        $lessonId = $this->route('lesson')->id;

        return [
            'questions'              => ['required', 'array', 'min:1'],
            'questions.*.id'         => [
                'required',
                'integer',
                Rule::exists('lesson_questions', 'id')->where('lesson_id', $lessonId),
            ],
            'questions.*.sort_order' => ['required', 'integer', 'min:0'],
        ];
    }
}
