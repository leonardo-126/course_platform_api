<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseAuthorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'                        => $this->id,
            'course_id'                 => $this->course_id,
            'user_id'                   => $this->user_id,
            'is_owner'                  => $this->is_owner,
            'can_view_student_progress' => $this->can_view_student_progress,
            'joined_at'                 => $this->joined_at,
            'user'                      => new UserResource($this->whenLoaded('user')),
        ];
    }
}
