<?php

namespace App\Repositories;

use App\Models\CourseCategory;
use Illuminate\Http\Request;

class CourseCategoryRepository
{
    protected $model;

    public function __construct(CourseCategory $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts = $this->model
                        ->withSearch($request->input('search'))
                        ->sortable(['updated_at' => 'desc'])
                        ->paginate($request->input('limit'));

        $posts->appends($request->all());

        return $posts;
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function getKeyId()
    {
        return $this->model->id;
    }

    // public function getItems()
    // {
    //     return $this->model->get()->pluck('title', 'id');
    // }

    // public static function getItemList()
    // {
    //     $repository = new self(new CourseCategory());
    //     dd($repository);
    //     $posts = $repository->getItems();
    //     return $posts;
    // }

    public function saveRecord($request, $id = null)
    {
        if (isset($id)) {
            $this->model = $this->find($id);
        }

        $this->model->fill($request->all());
        
        $this->model->save();
    }
}
