<?php
namespace App\Actions\CourseSection;
use App\Models\Course;
use App\Models\CourseSection;

class CreateCourseSectionAction
{
    public function execute(int $courseId, array $data): CourseSection
    {
        $course = Course::findOrFail($courseId);

        $sortOrder = $data['sort_order'] ?? ($course->sections()->max('sort_order') + 1);

        return $course->sections()->create([
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
            'sort_order'  => $sortOrder,
        ]);
    }
}