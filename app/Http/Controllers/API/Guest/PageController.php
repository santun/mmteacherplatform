<?php

namespace App\Http\Controllers\API\Guest;

use App\Models\Page;
use App\Http\Requests\RequestPage as Request;
use App\Http\Controllers\Controller;
use App\Repositories\PageRepository;
use App\Http\Resources\PageResource;
use App\Http\Resources\PageCollection;

class PageController extends Controller
{
    protected $repository;

    public function __construct(PageRepository $repository)
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
        $posts = $this->repository->apiPublishedOnly(request()); // $this->repository->index(request());

        return new PageCollection($posts);
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

        return new PageResource($post);
    }
}