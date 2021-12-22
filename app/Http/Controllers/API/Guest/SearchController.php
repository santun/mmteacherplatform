<?php

namespace App\Http\Controllers\API\Guest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\ResourceRepository;
use App\Models\Resource;
use App\Http\Resources\ResourceResource;

class SearchController extends Controller
{
    protected $repository;

    public function __construct(ResourceRepository $repository)
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
        $posts = $this->repository->searchForPublic(request(), currentUserType());

        return ResourceResource::collection($posts);
    }
}
