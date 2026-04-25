<?php

namespace App\Actions\CourseSection;
use App\Models\CourseSection;

class UpdateCourseSectionAction
{
    public function execute(int $sectionId, array $data): CourseSection
    {
        $section = CourseSection::findOrFail($sectionId);
        $section->update($data);
        return $section->refresh();
    }
}