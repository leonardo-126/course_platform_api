<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonQuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            'lesson_id'     => $this->lesson_id,
            'question_text' => $this->question_text,
            'question_type' => $this->question_type,
            'points'        => $this->points,
            'sort_order'    => $this->sort_order,
            'options'       => LessonQuestionOptionResource::collection(
                $this->whenLoaded('options', fn() => $this->options->sortBy('sort_order')->values())
            ),
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}
