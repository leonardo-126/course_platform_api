<?php

namespace App\Actions\Lesson;

use App\Models\CourseSection;
use App\Models\Lesson;
class CreateLessonAction
{
    public function execute(int $sectionId, array $data): Lesson
    {
        $section = CourseSection::findOrFail($sectionId);

        $sortOrder = $data['sort_order']
            ?? ($section->lessons()->max('sort_order') + 1);

        return $section->lessons()->create([
            'title'            => $data['title'],
            'type'             => $data['type'],
            'content'          => $data['content'] ?? null,
            'video_url'        => $data['video_url'] ?? null,
            'duration_minutes' => $data['duration_minutes'] ?? 0,
            'sort_order'       => $sortOrder,
            'is_preview'       => $data['is_preview'] ?? false,
        ]);
    }
}