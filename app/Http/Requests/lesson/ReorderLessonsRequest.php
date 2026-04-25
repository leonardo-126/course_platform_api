<?php

namespace App\Http\Requests\lesson;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReorderLessonsRequest extends FormRequest
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
        $sectionId = $this->route('section')->id;

        return [
            'lessons'              => ['required', 'array', 'min:1'],
            'lessons.*.id'         => [
                'required',
                'integer',
                Rule::exists('lessons', 'id')->where('course_section_id', $sectionId),
            ],
            'lessons.*.sort_order' => ['required', 'integer', 'min:0'],
        ];
    }
}
