<?php

namespace App\Actions\Lesson;

use App\Models\Lesson;

class DeleteLessonAction
{
    public function execute(int $lessonId): void
    {
        $lesson = Lesson::findOrFail($lessonId);
        $lesson->delete();
    }
}
