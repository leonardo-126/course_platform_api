<?php

namespace App\Actions\Course;

use App\Models\Course;
use App\Models\CourseAuthor;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class AddCourseAuthorAction
{
    public function execute(int $courseId, array $data): CourseAuthor
    {
        return DB::transaction(function () use ($courseId, $data) {
            $course = Course::findOrFail($courseId);

            $alreadyAuthor = $course->authorEntries()
                ->where('user_id', $data['user_id'])
                ->exists();

            if ($alreadyAuthor) {
                throw new RuntimeException('User is already an author of this course.');
            }

            return $course->authorEntries()->create([
                'user_id'                   => $data['user_id'],
                'is_owner'                  => false,
                'can_view_student_progress' => $data['can_view_student_progress'] ?? true,
                'joined_at'                 => now(),
            ]);
        });
    }
}
