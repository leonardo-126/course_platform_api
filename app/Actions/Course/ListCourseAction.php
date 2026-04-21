<?php

namespace App\Actions\Course;
use App\Models\Course;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListCourseAction
{
    public function execute(int $perPage = 15): LengthAwarePaginator
    {
        return Course::where('status', 'published')
            ->orderByDesc('published_at')
            ->paginate($perPage);
    }
}