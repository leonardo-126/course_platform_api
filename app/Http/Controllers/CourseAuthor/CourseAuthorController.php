<?php

namespace App\Http\Controllers\CourseAuthor;

use App\Actions\Course\AddCourseAuthorAction;
use App\Actions\Course\RemoveCourseAuthorAction;
use App\Actions\Course\UpdateCourseAuthorAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\courseAuthor\StoreCourseAuthorRequest;
use App\Http\Requests\courseAuthor\UpdateCourseAuthorRequest;
use App\Http\Resources\CourseAuthorResource;
use App\Models\Course;
use App\Models\CourseAuthor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseAuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Course $course): JsonResponse
    {
        return CourseAuthorResource::collection(
            $course->authorEntries()->with('user')->get()
        )->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        StoreCourseAuthorRequest $request,
        Course $course,
        AddCourseAuthorAction $action
    ): JsonResponse {
        $author = $action->execute($course->id, $request->validated());
        return (new CourseAuthorResource($author))->response()->setStatusCode(201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateCourseAuthorRequest $request,
        Course $course,
        CourseAuthor $author,
        UpdateCourseAuthorAction $action
    ): CourseAuthorResource {
        abort_if($author->course_id !== $course->id, 404);
        $updated = $action->execute($author->id, $request->validated());
        return new CourseAuthorResource($updated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Course $course,
        CourseAuthor $author,
        RemoveCourseAuthorAction $action
    ): JsonResponse {
        abort_if($author->course_id !== $course->id, 404);
        $action->execute($author->id);
        return response()->json(null, 204);
    }
}
