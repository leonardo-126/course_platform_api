<?php

namespace App\Actions\Course;
use App\Models\Course;

class ListCourseCreateByUserAction
{
    public function execute(int $userId): array
    {
        return Course::where('created_by', $userId)->get()->toArray();
    }
}