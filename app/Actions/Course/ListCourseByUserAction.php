<?php
namespace App\Actions\Course;
use App\Models\Course;
use Illuminate\Database\Eloquent\Collection;

class ListCourseByUserAction
{
    public function execute(int $userId): Collection
    {
          return Course::whereHas('authors', fn($q) => $q->where('users.id', $userId))
            ->with('authorEntries:id,course_id,user_id,is_owner')
            ->orderByDesc('updated_at')
            ->get();
    }
}