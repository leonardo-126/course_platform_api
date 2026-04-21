<?php

namespace App\Actions\CourseAuthor;

use App\Models\CourseAuthor;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class RemoveCourseAuthorAction
{
    public function execute(int $courseAuthorId): void
    {
        DB::transaction(function () use ($courseAuthorId) {
            $author = CourseAuthor::findOrFail($courseAuthorId);

            if ($author->is_owner) {
                throw new RuntimeException('Cannot remove the course owner.');
            }

            $author->delete();
        });
    }
}
