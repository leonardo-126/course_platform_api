<?php

namespace App\Actions\LessonQuestion;

use App\Models\LessonQuestion;
use Illuminate\Support\Facades\DB;

class ReorderLessonQuestionsAction
{
    public function execute(array $questions): void
    {
        DB::transaction(function () use ($questions) {
            foreach ($questions as $item) {
                LessonQuestion::where('id', $item['id'])
                    ->update(['sort_order' => $item['sort_order']]);
            }
        });
    }
}