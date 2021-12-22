<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CoursePrivacy;
use App\Models\Lecture;
use App\Repositories\CourseRepository;
use App\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use mysql_xdevapi\Collection;
use Spatie\MediaLibrary\Models\Media;

class CourseController extends Controller
{
    private $repository;
    private $currentUserType;

    public function __construct(CourseRepository $repository)
    {
        $this->repository = $repository;
        $this->middleware(function($request, $next){
            if(auth()->check()) {
                $this->currentUserType = auth()->user()->type;
            } else {
                $this->currentUserType = 'guest';
            }
            return $next($request);
        });
    }

    public function index()
    {
        $levels = Course::LEVELS;
        $courseCategories = CourseCategory::all();

        $user_type = currentUserType();

        if ($user_type == User::TYPE_ADMIN) {
            $how_to_slug = 'how-to-use-elearning-for-administrators';
        } elseif ($user_type == User::TYPE_MANAGER) {
            $how_to_slug = 'how-to-use-elearning-for-managers';
        } elseif ($user_type == User::TYPE_TEACHER_EDUCATOR) {
            $how_to_slug = 'how-to-use-elearning-for-teacher-educators';
        } elseif ($user_type == User::TYPE_STUDENT_TEACHER) {
            $how_to_slug = 'how-to-use-elearning-for-student-teachers';
        } else {
            $how_to_slug = 'how-to-use-elearning-for-guests';
        }

        return view('frontend.courses.index', compact('levels', 'courseCategories', 'how_to_slug'));
    }

    public function show(Course $course)
    {
        if(!$course->is_published) {
            return redirect()->back()->with('message', 'This course is currently unpublished');
        }

        if($this->repository->isAccessible($course, $this->currentUserType)) {
            $lectures = $course->lectures;
            $lecturesMedias = Media::all()->where('model_type', Lecture::class);
            return view('frontend.courses.show', compact('course', 'lectures', 'lecturesMedias'));
        }
        return \response()->json(['error' => 'invalid accessible right']);
    }

    public function filterCourses(Request $request)
    {
        $courses = Course::query()->where('is_published', true)
            ->where('approval_status', 1)->with('privacies');

        if($request->courseCategories) {
            $courses = $courses->whereIn('course_category_id', $request->courseCategories);
        }

        if($request->courseLevels) {
            $courses = $courses->whereIn('level_id', $request->courseLevels);
        }

        if($request->keyword) {
            $courses = $courses->where('title', 'LIKE', '%'.$request->keyword.'%');
        }

        $courseCollection = collect();

        foreach ($courses->latest()->get() as $course) {
            foreach ($course->privacies as $privacy) {
                if($privacy->user_type == $this->currentUserType) {
                    $courseCollection->push($course);
                    break;
                }
            }
        }

        $courseCollectionArray = CourseResource::collection($this->collectionPaginator($courseCollection, 5));

        return $courseCollectionArray;
    }

    public function takeCourse(Course $course)
    {
        if(!$course->is_published) {
            return redirect()->back()->with('message', 'This course is currently unpublished');
        }

        if($this->repository->isAccessible($course, $this->currentUserType)) {
            $user = auth()->user();

            if(CourseRepository::isAlreadyTakenCourse($user, $course)) {
                return redirect()->route('courses.show', $course)->with('message', 'You Already Take This Course');
            }

            $user->learningCourses()->attach($course->id, [
                'status' => 'not_started'
            ]);

            return redirect(CourseRepository::goToLastLecture($user, $course));
        }
        return response()->json(['error' => 'invalid accessible right']);
    }

    public function myCourses(Request $request)
    {
        $courseCategories = CourseCategory::all();
        $user = auth()->user();
        $courses = $user->learningCourses()->isPublished();

        if($request->course_category) {
            $courses = $courses->where('course_category_id', $request->course_category);
        }

        if($request->progress) {
            $courses = $courses->wherePivot('status', $request->progress);
        }

        if($request->sort_by) {
            $courses = $courses->orderBy($request->sort_by);
        }

        $courses = $courses->paginate(6);

        $userLectures = $user->learningLectures;

        return view('frontend.courses.my-courses', compact('courses', 'userLectures', 'courseCategories', 'request'));
    }

    public function learnCourse(Course $course, Lecture $lecture)
    {
        if(!$course->is_published) {
            return redirect()->back()->with('message', 'This course is currently unpublished');
        }

        $user = auth()->user();

        if (!$course) {
            return redirect()->route('courses.my-courses')->with('message', 'This course is not taken!');
        }

        if (! $user->learningLectures->contains('id', $lecture->id)) {
            $user->learningLectures()->attach($lecture->id);
        }

        $lectures = $course->lectures()->orderBy('id')->get();
        $lecturesMedias = Media::all()->where('model_type', Lecture::class);
        $currentLecture = $lecture;
        $nextLecture = $course->lectures()->orderBy('id')->where('id', '>', $currentLecture->id)->first();

        //dd($currentLecture->quizzes);

        $status = $nextLecture ? 'learning' : 'completed';

        $user->learningCourses()->updateExistingPivot($course->id, [
            'status' => $status
        ]);

        $previousLecture = $course->lectures()->orderBy('id', 'desc')->where('id', '<', $currentLecture->id)->first();
        $userLectures = $user->learningLectures;

        $downloadOption = $course->downloadable_option;

        return view('frontend.courses.course-learning-page',
            compact('course', 'lectures', 'lecturesMedias', 'currentLecture', 'nextLecture', 'previousLecture',
                'userLectures', 'downloadOption'
            ));
    }

    public function downloadLecture(Lecture $lecture)
    {
        return response()->download($lecture->getMedia('lecture_attached_file')->first()->getPath(), $lecture->media->first()->file_name);
    }

    public function downloadCourse(Course $course)
    {
        if(!$course->is_published) {
            return redirect()->back()->with('message', 'This course is currently unpublished');
        }

        if(file_exists($course->getMedia('course_resource_file')->first()->getPath())) {
            return response()->download($course->getMedia('course_resource_file')->first()->getPath(), $course->media()->first()->filename);
        }

        return response()->json(['error' => 'file not found']);
    }

    private function collectionPaginator($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof \Illuminate\Support\Collection ? $items : \Illuminate\Support\Collection::make($items);
        return (new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options))
            ->withPath(asset('/e-learning/courses'));
    }
}
