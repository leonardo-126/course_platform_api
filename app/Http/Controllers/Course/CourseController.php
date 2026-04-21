<?php

namespace App\Http\Controllers\Course;

use App\Actions\Course\CreateCourseAction;
use App\Actions\Course\DeleteCourseAction;
use App\Actions\Course\UpdateCourseAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\course\StoreCourseRequest;
use App\Http\Requests\course\UpdateCourseRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $courses = Course::where('status', 'published')->paginate(15);
        return CourseResource::collection($courses)->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request, CreateCourseAction $action): JsonResponse
    {
        $course = $action->execute($request->validated());

        return (new CourseResource($course))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course): CourseResource
    {
        return new CourseResource($course->load('authorEntries.user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, Course $course, UpdateCourseAction $action): CourseResource
    {
        $updated = $action->execute($course->id, $request->validated());
        return new CourseResource($updated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course, DeleteCourseAction $action): JsonResponse
    {
        $action->execute($course->id);
        return response()->json(null, 204);
    }
}
