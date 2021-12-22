<?php

namespace App\Http\Controllers\API\Guest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\SubjectRepository;
use App\Models\Subject;
use App\Http\Resources\SubjectResource;
use App\Http\Resources\SubjectCollection;
use App\Http\Resources\ResourceResource;

class SubjectController extends Controller
{
    protected $repository;

    public function __construct(SubjectRepository $repository)
    {
        $this->repository = $repository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $posts = $this->repository->apiPublishedOnly(request());//$this->repository->publishedOnly(request());

        // Get translated strings
        $posts->map(function ($post) {
            $post->title = __($post->title);
            return $post;
        });

        return new SubjectCollection($posts);
    }

    public function getSubjects()
    {
        return response()->json($this->repository->getItems());//new SubjectCollection($this->repository->getAllPublished());
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        if (!$post = $this->repository->findBySlug($slug)) {
            return response()->json(['message' => 'Resource Not Found'], 404);
        }

        return new SubjectResource($post);
    }

    public function getResources($slug)
    {
        if (!$post = $this->repository->findBySlug($slug)) {
            return response()->json(['message' => 'Resource Not Found'], 404);
        }

        $posts = $this->repository->getResourcesWithSubject($post->id);

        return ResourceResource::collection($posts);
    }
}
