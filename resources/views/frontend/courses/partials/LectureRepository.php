<?php

namespace App\Repositories;

use ConvertApi\ConvertApi;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CoursePrivacy;
use App\Models\Lecture;
use App\Jobs\UploadVideoToVimeo;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpOffice\PhpPresentation\IOFactory;


class LectureRepository
{
    protected $model;

    public function __construct(Lecture $model)
    {
        $this->model = $model;
    }

    public function saveRecord($request, $id = null)
    {
        if (isset($id)) {
            $this->model = $this->find($id);
        } else {
            $this->model->user_id = auth()->user()->id;
        }

        $this->model->lecture_title = $request->lecture_title;
        $this->model->resource_link = $request->resource_link;
        $this->model->description = $request->description;
        $this->model->course_id = $request->course_id;
        $this->model->uuid = Str::uuid(Carbon::now());

        // $file_name = "";
        // if ($file = $request->file('attached_file')) {
        //     $file_name = $file->getClientOriginalName();
        //     $file_type = $file->getClientOriginalExtension();
        //     $this->model->attached_file = $file_name;
        //     $this->model->media_type = $file_type;
        // }

        $this->model->save();

        // if ($this->model->save() && !empty($file_name)) {
        //     $path = 'assets/course/lecture/attachement/'.$this->getKeyId();
        //     $file->move($path, $file_name);
        // }

        // Upload to Vimeo (if the file is video)
        $allowExtensions = ['mp4', 'mpg', 'mpeg', 'wmv', 'avi', 'mov'];

        if ($request->file('attached_file')) {
            $extension = $request->file('attached_file')->getClientOriginalExtension(); //->getMimeType();
            $mimes_type = $request->file('attached_file')->getMimeType();
            \Log::info($mimes_type);
            // Store media file first
            $this->model->addMediaFromRequest('attached_file')->toMediaCollection('lecture_attached_file');

            if (in_array($extension, $allowExtensions)) {
                dispatch(new UploadVideoToVimeo($this->model, 'lecture_attached_file'));
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

    public function getByCourse($request, $course_id)
    {
        $posts = $this->model->where('course_id', $course_id)
                                ->sortable(['created_at' => 'asc'])
                                ->get();
                                // ->paginate($request->input('limit'));
        // $posts->appends($request->all());
        return $posts;
    }

    public function convertPresentationFile($file, $lecture)
    {
        $converterApiKey = env('CONVERTER_API_KEY');
        ConvertApi::setApiSecret($converterApiKey);
        $result = ConvertApi::convert('pdf', ['File' => $file]);
        // $specialChars = [' ', '?', '!', '~', '@', '#', '$', '%', '^', '&', '*', '(', ')', '?', '/','\'', '|', ',', '"'];

        // $result->getFile()->save(public_path('/upload/' . str_replace($specialChars, '', $lecture->course->title . '-' . $lecture->lecture_title) . '.pdf'));
        $result->getFile()->save(public_path('/upload/' . $lecture->course->id . '-' . $lecture->uuid) . '.pdf');
    }

}
