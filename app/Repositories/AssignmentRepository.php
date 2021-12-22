<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CoursePrivacy;
use App\Models\Assignment;
use App\Jobs\UploadVideoToVimeo;
use App\User;
use Carbon\Carbon;
use DB;


class AssignmentRepository
{
    protected $model;

    public function __construct(Assignment $model)
    {
        $this->model = $model;
    }

    public function saveRecord($request, $id = null)
    {
        // dd($request->all());
        if (isset($id)) {
            $this->model = $this->find($id);
        } else {
            $this->model->user_id = auth()->user()->id;
        }

        $this->model->title = $request->title;
        $this->model->course_id = $request->course_id;
        $this->model->description = $request->description;
        $this->model->save();  
        if ($file = $request->file('attached_file')) {
            $this->model->addMediaFromRequest('attached_file')->toMediaCollection('assignment_attached_file');
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

    public function getByCourse($request, $course_id)
    {
        $posts = $this->model->where('course_id', $course_id)
                                ->latest()
                                ->get();
        //                         ->paginate($request->input('limit'));
        // $posts->appends($request->all());
        return $posts;
    }

    /**
     * Check if user can edit the resource
     *
     * @param App\Models\Resource $course
     * @return boolean
     */
    public static function canEdit($assignment)
    {
        if (!$user = auth()->user()) {
            return false;
        }
        $course = Course::findOrFail($assignment->course_id);
        if (count($assignment->assignment_user) == 0){
            return true;
        }

        return false;
    }

    /**
     * Check if user can review the resource
     *
     * @param App\Models\Resource $course
     * @return boolean
     */
    public static function canReview($assignment)
    {
        if (!$user = auth()->user()) {
            return false;
        }
        $course = Course::findOrFail($assignment->course_id);
        // dd($user->id. ' # ' .$course->user_id);
        if ($user->isAdmin() || $user->isManager() || $user->id == $course->user_id){
            return true;
        }

        return false;
    }

}
