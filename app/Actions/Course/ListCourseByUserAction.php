<?php
namespace App\Actions\Course;
use App\Models\Course;

class ListCourseByUserAction
{
    public function execute(int $userId): array
    {
        return Course::whereHas('authors', fn($q) => $q->where('users.id', $userId))
        ->get()->toArray();
    }
}