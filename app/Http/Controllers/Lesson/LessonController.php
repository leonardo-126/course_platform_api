<?php

namespace App\Http\Controllers\Lesson;

use App\Actions\Lesson\CreateLessonAction;
use App\Actions\Lesson\DeleteLessonAction;
use App\Actions\Lesson\ReorderLessonsAction;
use App\Actions\Lesson\UpdateLessonAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\lesson\ReorderLessonsRequest;
use App\Http\Requests\lesson\StoreLessonRequest;
use App\Http\Requests\lesson\UpdateLessonRequest;
use App\Http\Resources\LessonResource;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Course $course, CourseSection $section): JsonResponse
    {
        abort_if($section->course_id !== $course->id, 404);
        $lessons = $section->lessons()->orderBy('sort_order')->get();
        return LessonResource::collection($lessons)->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        StoreLessonRequest $request,
        Course $course,
        CourseSection $section,
        CreateLessonAction $action
    ): JsonResponse {
        abort_if($section->course_id !== $course->id, 404);

        $lesson = $action->execute($section->id, $request->validated());
        return (new LessonResource($lesson))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course, CourseSection $section, Lesson $lesson): LessonResource
    {
        abort_if($section->course_id !== $course->id, 404);
        abort_if($lesson->course_section_id !== $section->id, 404);

        return new LessonResource($lesson);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateLessonRequest $request,
        Course $course,
        CourseSection $section,
        Lesson $lesson,
        UpdateLessonAction $action
    ): LessonResource {
        abort_if($section->course_id !== $course->id, 404);
        abort_if($lesson->course_section_id !== $section->id, 404);

        $updated = $action->execute($lesson->id, $request->validated());
        return new LessonResource($updated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Course $course,
        CourseSection $section,
        Lesson $lesson,
        DeleteLessonAction $action
    ): JsonResponse {
        abort_if($section->course_id !== $course->id, 404);
        abort_if($lesson->course_section_id !== $section->id, 404);

        $action->execute($lesson->id);
        return response()->json(null, 204);
    }

    public function reorder(
        ReorderLessonsRequest $request,
        Course $course,
        CourseSection $section,
        ReorderLessonsAction $action
    ): JsonResponse {
        abort_if($section->course_id !== $course->id, 404);

        $action->execute($request->validated()['lessons']);
        return response()->json(null, 204);
    }
}
