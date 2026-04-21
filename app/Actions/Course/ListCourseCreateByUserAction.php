<?php

namespace App\Actions\Course;

use App\Models\Course;
use Illuminate\Database\Eloquent\Collection;

class ListCourseCreateByUserAction
{
    public function execute(int $userId): Collection
    {
        return Course::where('created_by', $userId)->orderByDesc('created_at')->get();
    }
}
