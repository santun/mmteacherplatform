<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CoursePrivacy;
use App\Models\College;
use App\User;
use Carbon\Carbon;
use DB;


class CourseRepository
{
    protected $model;

    public function __construct(Course $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the course.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts = $this->model
                        ->where(function ($query) {
                            $query->where('user_id', '=', auth()->user()->id);
                            $query->orWhere(function ($query) {
                                $query->where('approval_status', '!=', null)
                                      ->where('user_id', '!=', auth()->user()->id);
                            });
                        })
                        ->withSearch($request->input('search'))
                        ->withApprovalStatus($request->input('approval_status'))
                        // ->where('approval_status', '!=', null)
                        ->withUploadedBy($request->input('uploaded_by'))
                        ->withCategory($request->input('course_category_id'))
                        ->withLevel($request->input('level_id'))
                        //->isPublished()
                        ->sortable(['created_at' => 'desc'])
                        // $request->input('limit')
                        ->paginate(10);

        $posts->appends($request->all());

        return $posts;
    }

    /**
     * Display a listing of the course.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexForManager(Request $request)
    {
        $user = auth()->user();

        if ($college = College::find($user->ec_college)) {
            $posts = $this->model
                        // ->with(['media'])
                        ->whereHas('user', function ($q) use ($user) {
                                $q->where('ec_college', '=', $user->ec_college);
                        })
                        ->where(function ($query) {
                            $query->where('user_id', '=', auth()->user()->id);
                            $query->orWhere(function ($query) {
                                $query->where('approval_status', '!=', null)
                                      ->where('user_id', '!=', auth()->user()->id);
                            });
                        })
                        ->withSearch($request->input('search'))
                        ->withUploadedBy($request->input('uploaded_by'))
                        ->withCategory($request->input('course_category_id'))
                        ->withApprovalStatus($request->input('approval_status'))
                        ->withLevel($request->input('level_id'))
                        ->sortable(['created_at' => 'desc'])
                        ->paginate($request->input('limit'));

            $posts->appends($request->all());
        }

        return $posts;
    }

    /**
     * Display a listing of the course.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexForMember(Request $request)
    {
        $posts = $this->model
                        // ->with(['media'])
                        ->ofUser(auth()->user()->id)
                        ->withSearch($request->input('search'))
                        ->withCategory($request->input('course_category_id'))
                        ->withLevel($request->input('level_id'))
                        ->withApprovalStatus($request->input('approval_status'))
                        ->sortable(['created_at' => 'desc'])
                        ->paginate($request->input('limit'));

        $posts->appends($request->all());

        return $posts;
    }

    public function saveRecord($request, $id = null)
    {
        if (isset($id)) {
            $this->model = $this->find($id);
        } else {
            $this->model->user_id = auth()->user()->id;
        }

        $this->model->fill($request->all());

        $this->model->is_published = $request->input('is_published', false);
        $this->model->allow_edit = $request->input('allow_edit', true);
        $this->model->is_locked = $request->input('is_locked', false);
        if ($request->has('approval_status')){
            $this->model->approval_status = $request->input('approval_status');
                $this->model->approved_by = null;
            if ($this->model->approval_status == Course::APPROVAL_STATUS_APPROVED ||
                $this->model->approval_status == Course::APPROVAL_STATUS_REJECTED) {
                $this->model->approved_at = Carbon::now();
                $this->model->approved_by = auth()->user()->id;
            }
        }

        // if ($request->has('approval_status') && !empty($request->input('approval_status'))) {
        //     $this->model->approval_status = $request->input('approval_status');

        //     if ($this->model->approval_status != Course::APPROVAL_STATUS_PENDING) {
        //         $this->model->approved_at = Carbon::now();
        //     }
        // } else{
        //     $this->model->approval_status = Course::APPROVAL_STATUS_PENDING;
        // }

        // $file_name = "";
        if ($file = $request->file('cover_image')) {
            // $file_name = $file->getClientOriginalName();
            // $this->model->cover_image = $file_name;
            $this->model->addMediaFromRequest('cover_image')->toMediaCollection('course_cover_image');
            $this->model->cover_image = '';
        }

        if ($file = $request->file('resource_file')) {
            $this->model->addMediaFromRequest('resource_file')->toMediaCollection('course_resource_file');
        }

        $this->model->save();

        // if ($this->model->save() && !empty($file_name)) {
        //     $path = 'assets/course/cover_image/'.$this->getKeyId();
        //     $file->move($path, $file_name);
        // }

        // Privacy
        //$privacy->resource()->associate($this->model);
        //$this->model->privacies()->sync($request->input('user_type'));

        $old_privacies = CoursePrivacy::where('course_id', $id)->get();

        foreach ($old_privacies as $old_privacy) {
            $old_privacy->delete();
        }

        if ($request->input('user_type')) {
            foreach ($request->input('user_type') as $user_type) {
                $privacy = new CoursePrivacy();
                $privacy->course_id = $this->model->id;
                $privacy->user_type = $user_type;
                $privacy->save();
            }
        }


    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function getKeyId()
    {
        return $this->model->id;
    }

    public function getDefaultRightsForCourseForm($user_type)
    {
        $rights = [];

        switch ($user_type) {
            case User::TYPE_ADMIN:
                $rights = [
                    User::TYPE_ADMIN
                ];
                break;

            case User::TYPE_MANAGER:
                $rights = [
                    User::TYPE_ADMIN, User::TYPE_MANAGER
                ];
                break;
            case User::TYPE_TEACHER_EDUCATOR:
                $rights = [
                    User::TYPE_ADMIN, User::TYPE_MANAGER, User::TYPE_TEACHER_EDUCATOR
                ];
                break;
        }

        return $rights;
    }

    public function checkForOtherUse($model)
    {
      if (count($model->quizzes) != 0) {
        return true;
      } else if(count($model->lecture) != 0){
        return true;
      }else if(count($model->assignment) != 0){
        return true;
      }
      return false;
    }

    public static function checkProgress(Course $course, $userLectures)
    {
        $totalCourseLecture = $course->lectures->count();
        $totalLearnLecture = $userLectures->where('course_id', $course->id)->count();
        return round($totalLearnLecture / $totalCourseLecture * 100);
    }

    public static function isAlreadyTakenCourse(User $user, Course $course)
    {
        return $user->learningCourses->contains('id', $course->id);
    }

    public static function goToLastLecture(User $user, Course $course)
    {
        $userLectures = $user->learningLectures;
        return $userLectures
            ->where('course_id', $course->id)->last() ? route('courses.learn-course',
            [$course, $userLectures->where('course_id', $course->id)->last()])
            : route('courses.learn-course', [$course, $course->lectures()->first()]);

    }

    public function destroy($id)
    {
        $this->model = $this->find($id);

        $this->destroyLectures();

        $this->destroyQuizzes();

        $this->destroyAssignments();

        $this->destroyLearnCourse();

        $this->model->delete();
    }

    protected function destroyLectures()
    {
        $this->model->lectures->each->delete();
    }

    protected function destroyQuizzes()
    {
        if ($this->model->quizzes) {
            $quizzes = $this->model->quizzes;
            foreach ($quizzes as $quiz) {
              $quiz->questions->each->delete();
              $quiz->delete();
            }
        }
    }

    protected function destroyAssignments()
    {
        if ($this->model->assignment) {
            $assignments = $this->model->assignment;
            foreach ($assignments as $assignment) {
              $assignment->assignment_user->each->delete();
              $assignment->delete();
            }
        }
    }

    protected function destroyLearnCourse()
    {
        $this->model->courseLearners()->detach();
    }

    public function isAccessible(Course $course, $userType)
    {
        foreach($course->privacies as $privacy) {
            if($privacy->user_type == $userType) {
                return true;
            }
        }

        return false;

    }

}
