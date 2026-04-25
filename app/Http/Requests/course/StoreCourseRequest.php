<?php

namespace App\Http\Requests\course;

use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Course::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'title'             => ['required', 'string', 'max:255'],
            'description'       => ['nullable', 'string'],
            'thumbnail'         => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'], // 2MB
            'estimated_minutes' => ['nullable', 'integer', 'min:0'],
            'xp_reward'         => ['nullable', 'integer', 'min:0'],
        ];
    }
    public function validated($key = null, $default = null): array
    {
        return array_merge(parent::validated($key, $default), [
            'created_by' => $this->user()->id,
        ]);
    }
}
