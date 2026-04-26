<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LessonQuestionOptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'question_id' => $this->question_id,
            'option_text' => $this->option_text,
            'is_correct'  => $this->is_correct,
            'sort_order'  => $this->sort_order,
        ];
    }
}
