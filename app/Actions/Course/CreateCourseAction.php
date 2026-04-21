<?php

namespace App\Actions\Course;

use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateCourseAction
{
    public function execute(array $data): Course
    {
        return DB::transaction(function () use ($data) {
            $course = Course::create([
                'created_by' => $data['created_by'],
                'title' => $data['title'],
                'description' => $data['description'],
                'slug' => $data['slug'] ?? Str::slug($data['title']) . '-' . Str::random(6),
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
