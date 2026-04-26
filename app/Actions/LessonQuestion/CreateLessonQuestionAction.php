<?php

namespace App\Actions\LessonQuestion;

use App\Models\Lesson;
use Illuminate\Support\Facades\DB;

class CreateLessonQuestionAction
{
    public function execute(int $lessonId, array $data)
    {
        return DB::transaction(function () use ($lessonId, $data) {
            $lesson = Lesson::findOrFail($lessonId);

            $sortOrder = $data['sort_order']
                ?? ($lesson->questions()->max('sort_order') + 1);

            $question = $lesson->questions()->create([
                'question_text' => $data['question_text'],
                'question_type' => $data['question_type'],
                'points'        => $data['points'] ?? 1,
                'sort_order'    => $sortOrder,
            ]);

            foreach ($data['options'] as $index => $opt) {
                $question->options()->create([
                    'option_text' => $opt['option_text'],
                    'is_correct'  => $opt['is_correct'],
                    'sort_order'  => $opt['sort_order'] ?? $index,
                ]);
            }


            return $question->load('options');
        });
    }
}
