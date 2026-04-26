<?php

namespace App\Actions\LessonQuestion;

use App\Models\LessonQuestion;

class DeleteLessonQuestionAction
{
    public function execute(int $questionId): void
    {
        $question = LessonQuestion::findOrFail($questionId);
        $question->delete();  // cascade
    }
}