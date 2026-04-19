<?php

namespace App\Actions\Course;

use App\Models\Course;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class TransferCourseOwnershipAction
{
    public function execute(int $courseId, int $newOwnerUserId): void
    {
        DB::transaction(function () use ($courseId, $newOwnerUserId) {
            $course = Course::findOrFail($courseId);

            $newOwnerEntry = $course->authorEntries()
                ->where('user_id', $newOwnerUserId)
                ->first();

            if ($newOwnerEntry === null) {
                throw new RuntimeException('Target user must already be an author of the course.');
            }

            $course->authorEntries()
                ->where('is_owner', true)
                ->update(['is_owner' => false]);

            $newOwnerEntry->update(['is_owner' => true]);

            $course->update(['created_by' => $newOwnerUserId]);
        });
    }
}
