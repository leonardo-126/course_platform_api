<?php

namespace App\Http\Requests\courseSection;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReorderCourseSectionsRequest extends FormRequest
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
        $courseId = $this->route('course')->id;

        return [
            'sections'              => ['required', 'array', 'min:1'],
            'sections.*.id'         => [
                'required',
                'integer',
                Rule::exists('course_sections', 'id')->where('course_id', $courseId),
            ],
            'sections.*.sort_order' => ['required', 'integer', 'min:0'],
        ];
    }
}
