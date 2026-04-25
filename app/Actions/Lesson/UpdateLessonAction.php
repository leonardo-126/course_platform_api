<?php 

namespace App\Actions\Lesson;
use App\Models\Lesson;

class UpdateLessonAction
{
    public function execute(int $lessonId, array $data): Lesson
    {
        $lesson = Lesson::findOrFail($lessonId);

        $lesson->update($data);
        return $lesson->refresh();
    }
}