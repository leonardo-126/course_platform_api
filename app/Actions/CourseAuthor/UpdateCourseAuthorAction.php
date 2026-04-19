<?php

namespace App\Actions\Course;

use App\Models\CourseAuthor;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class UpdateCourseAuthorAction
{
    public function execute(int $courseAuthorId, array $data): CourseAuthor
    {
        return DB::transaction(function () use ($courseAuthorId, $data) {
            $author = CourseAuthor::findOrFail($courseAuthorId);

            if ($author->is_owner) {
                throw new RuntimeException('Cannot modify the owner through this action. Use a transfer-ownership action instead.');
            }

            $author->update([
                'can_view_student_progress' => $data['can_view_student_progress'] ?? $author->can_view_student_progress,
            ]);

            return $author->refresh();
        });
    }
}
