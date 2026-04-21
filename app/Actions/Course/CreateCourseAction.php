<?php

namespace App\Actions\Course;

use App\Models\Course;
use App\Actions\Course\CourseThumbnailService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateCourseAction
{

    public function __construct(private CourseThumbnailService $thumbnails) {}

    public function execute(array $data): Course
    {
        return DB::transaction(function () use ($data) {
            $thumbnailUrl = isset($data['thumbnail'])
                ? $this->thumbnails->store($data['thumbnail'])
                : null;

            $course = Course::create([
                'created_by'        => $data['created_by'],
                'title'             => $data['title'],
                'slug'              => $data['slug'] ?? Str::slug($data['title']) . '-' . Str::random(6),
                'description'       => $data['description'] ?? null,
                'thumbnail_url'     => $thumbnailUrl,
                'estimated_minutes' => $data['estimated_minutes'] ?? 0,
                'xp_reward'         => $data['xp_reward'] ?? 0,
            ]);
            $course->authors()->attach($data['created_by'], [
                'is_owner' => true,
                'can_view_student_progress' => true,
                'joined_at' => now(),
            ]);
            return $course;
        });
    }
}
