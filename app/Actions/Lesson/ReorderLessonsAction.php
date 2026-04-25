<?php

namespace App\Actions\Lesson;

use App\Models\Lesson;
use Illuminate\Support\Facades\DB;

class ReorderLessonsAction
{
    public function execute(array $lessons): void
    {
        DB::transaction(function () use ($lessons) {
            foreach ($lessons as $item) {
                Lesson::where('id', $item['id'])
                    ->update(['sort_order' => $item['sort_order']]);
            }
        });
    }
}