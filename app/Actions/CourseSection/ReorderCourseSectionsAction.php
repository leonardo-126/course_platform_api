<?php

namespace App\Actions\CourseSection;

use App\Models\CourseSection;
use Illuminate\Support\Facades\DB;

class ReorderCourseSectionsAction
{
    public function execute(array $sections): void
    {
        DB::transaction(function () use ($sections) {
            foreach ($sections as $item) {
                CourseSection::where('id', $item['id'])
                    ->update(['sort_order' => $item['sort_order']]);
            }
        });
    }
}