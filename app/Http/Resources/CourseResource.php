<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'title'             => $this->title,
            'slug'              => $this->slug,
            'description'       => $this->description,
            'thumbnail_url'     => $this->thumbnail_url,
            'estimated_minutes' => $this->estimated_minutes,
            'xp_reward'         => $this->xp_reward,
            'status'            => $this->status,
            'published_at'      => $this->published_at,
            'created_by'        => $this->created_by,
            'authors'           => CourseAuthorResource::collection($this->whenLoaded('authorEntries')),
        ];
    }
}
