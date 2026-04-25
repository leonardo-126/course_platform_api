<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'                => $this->id,
            'course_section_id' => $this->course_section_id,
            'title'             => $this->title,
            'type'              => $this->type,
            'content'           => $this->content,
            'video_url'         => $this->video_url,
            'duration_minutes'  => $this->duration_minutes,
            'sort_order'        => $this->sort_order,
            'is_preview'        => $this->is_preview,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }
}
