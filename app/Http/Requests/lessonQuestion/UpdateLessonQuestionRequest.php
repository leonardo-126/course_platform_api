<?php

namespace App\Http\Requests\lessonQuestion;

use App\Rules\HasOneCorrectOption;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLessonQuestionRequest extends FormRequest
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
            'question_text' => ['sometimes', 'string', 'max:1000'],
            'question_type' => ['sometimes', Rule::in(['single_choice'])],
            'points'        => ['sometimes', 'integer', 'min:1'],
            'sort_order'    => ['sometimes', 'integer', 'min:0'],

            'options'              => ['sometimes', 'array', 'min:2', 'max:10', new HasOneCorrectOption],
            'options.*.id'         => ['sometimes', 'integer'], // se vier, é update; se não, é create
            'options.*.option_text'=> ['required_with:options', 'string', 'max:500'],
            'options.*.is_correct' => ['required_with:options', 'boolean'],
            'options.*.sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
