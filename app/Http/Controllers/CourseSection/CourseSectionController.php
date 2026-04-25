<?php

namespace App\Http\Controllers\CourseSection;

use App\Actions\CourseSection\CreateCourseSectionAction;
use App\Actions\CourseSection\DeleteCourseSectionAction;
use App\Actions\CourseSection\ReorderCourseSectionsAction;
use App\Actions\CourseSection\UpdateCourseSectionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\courseSection\ReorderCourseSectionsRequest;
use App\Http\Requests\courseSection\StoreCourseSectionRequest;
use App\Http\Requests\courseSection\UpdateCourseSectionRequest;
use App\Http\Resources\CourseSectionResource;
use App\Models\Course;
use App\Models\CourseSection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseSectionController extends Controller
{
    public function index(Course $course)
    {
        $sections = $course->sections()->orderBy('sort_order')->get();
        return CourseSectionResource::collection($sections)->response();
    }

    public function show(Course $course, CourseSection $section): CourseSectionResource
    {
        abort_if($section->course_id !== $course->id, 404);
        return new CourseSectionResource($section);
    }

    public function store(
        StoreCourseSectionRequest $request,
        Course $course,
        CreateCourseSectionAction $action
    ): JsonResponse {
        $section = $action->execute($course->id, $request->validated());
        return (new CourseSectionResource($section))->response()->setStatusCode(201);
    }

     public function update(
        UpdateCourseSectionRequest $request,
        Course $course,
        CourseSection $section,
        UpdateCourseSectionAction $action
    ): CourseSectionResource {
        abort_if($section->course_id !== $course->id, 404);
        $updated = $action->execute($section->id, $request->validated());
        return new CourseSectionResource($updated);
    }

    public function destroy(
        Course $course,
        CourseSection $section,
        DeleteCourseSectionAction $action
    ): JsonResponse {
        abort_if($section->course_id !== $course->id, 404);
        $action->execute($section->id);
        return response()->json(null, 204);
    }

    public function reorder(
        ReorderCourseSectionsRequest $request,
        Course $course,
        ReorderCourseSectionsAction $action
    ): JsonResponse {
        $action->execute($request->validated()['sections']);
        return response()->json(null, 204);
    }
}
