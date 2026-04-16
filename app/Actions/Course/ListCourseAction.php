<?php

namespace App\Actions\Course;
use App\Models\Course;

class ListCourseAction
{
    public function execute(): array
    {
        return Course::all()->toArray();
    }
}