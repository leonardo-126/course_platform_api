<?php

namespace App\Actions\Course;

use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateCourseAction
{
    public function execute(int $courseId, array $data): Course
    {
        return DB::transaction(function () use ($courseId, $data) {
            $course = Course::findOrFail($courseId);

            if (isset($data['title']) && ! isset($data['slug'])) {
                $data['slug'] = Str::slug($data['title']);
            }

            if (isset($data['status']) && $data['status'] === 'published' && $course->published_at === null) {
                $data['published_at'] = now();
            }

            $course->update($data);

            return $course->refresh();
        });
    }
}