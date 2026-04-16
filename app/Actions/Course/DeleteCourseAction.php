<?php

namespace App\Actions\Course;

use App\Models\Course;

class DeleteCourseAction
{
    public function execute(int $courseId): Course
    {
        $course = Course::findOrFail($courseId);

        $course->update(['status' => Course::STATUS_ARCHIVED]);

        return $course;
    }
}
