<?php

namespace App\Http\Controllers\LessonQuestion;

use App\Actions\LessonQuestion\CreateLessonQuestionAction;
use App\Actions\LessonQuestion\DeleteLessonQuestionAction;
use App\Actions\LessonQuestion\ReorderLessonQuestionsAction;
use App\Actions\LessonQuestion\UpdateLessonQuestionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\lessonQuestion\ReorderLessonQuestionsRequest;
use App\Http\Requests\lessonQuestion\StoreLessonQuestionRequest;
use App\Http\Requests\lessonQuestion\UpdateLessonQuestionRequest;
use App\Http\Resources\LessonQuestionResource;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Lesson;
use App\Models\LessonQuestion;
use Illuminate\Http\JsonResponse;

class LessonQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Course $course, CourseSection $section, Lesson $lesson): JsonResponse
    {
        $this->validateHierarchy($course, $section, $lesson);

        $questions = $lesson->questions()->with('options')->orderBy('sort_order')->get();

        return LessonQuestionResource::collection($questions)->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        StoreLessonQuestionRequest $request,
        Course $course,
        CourseSection $section,
        Lesson $lesson,
        CreateLessonQuestionAction $action
    ): JsonResponse {
        $this->validateHierarchy($course, $section, $lesson);

        $question = $action->execute($lesson->id, $request->validated());
        return (new LessonQuestionResource($question))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(
        Course $course,
        CourseSection $section,
        Lesson $lesson,
        LessonQuestion $question
    ): LessonQuestionResource {
        $this->validateHierarchy($course, $section, $lesson, $question);
        return new LessonQuestionResource($question->load('options'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateLessonQuestionRequest $request,
        Course $course,
        CourseSection $section,
        Lesson $lesson,
        LessonQuestion $question,
        UpdateLessonQuestionAction $action
    ): LessonQuestionResource {
        $this->validateHierarchy($course, $section, $lesson, $question);

        $updated = $action->execute($question->id, $request->validated());
        return new LessonQuestionResource($updated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Course $course,
        CourseSection $section,
        Lesson $lesson,
        LessonQuestion $question,
        DeleteLessonQuestionAction $action
    ): JsonResponse {
        $this->validateHierarchy($course, $section, $lesson, $question);

        $action->execute($question->id);
        return response()->json(null, 204);
    }

    /**
     * Reorder the specified resources in storage.
     */
    public function reorder(
        ReorderLessonQuestionsRequest $request,
        Course $course,
        CourseSection $section,
        Lesson $lesson,
        ReorderLessonQuestionsAction $action
    ): JsonResponse {
        $this->validateHierarchy($course, $section, $lesson);

        $action->execute($request->validated()['questions']);
        return response()->json(null, 204);
    }

    private function validateHierarchy(
        Course $course,
        CourseSection $section,
        Lesson $lesson,
        ?LessonQuestion $question = null
    ): void {
        abort_if($section->course_id !== $course->id, 404);
        abort_if($lesson->course_section_id !== $section->id, 404);
        abort_if($question !== null && $question->lesson_id !== $lesson->id, 404);
    }
}
