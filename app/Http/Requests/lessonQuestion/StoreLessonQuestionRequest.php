<?php

namespace App\Http\Requests\lessonQuestion;

use App\Rules\HasOneCorrectOption;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLessonQuestionRequest extends FormRequest
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
            'question_text'        => ['required', 'string', 'max:1000'],
            'question_type'        => ['required', Rule::in(['single_choice'])],
            'points'               => ['nullable', 'integer', 'min:1'],
            'sort_order'           => ['nullable', 'integer', 'min:0'],

            'options'              => ['required', 'array', 'min:2', 'max:10', new HasOneCorrectOption],
            'options.*.option_text' => ['required', 'string', 'max:500'],
            'options.*.is_correct' => ['required', 'boolean'],
            'options.*.sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
