<?php 

namespace App\Actions\CourseSection;

use App\Models\CourseSection;

class DeleteCourseSectionAction
{
    public function execute(int $sectionId): void
    {
        $section = CourseSection::findOrFail($sectionId);
        $section->delete();
    }
}