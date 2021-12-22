<?php

namespace App\Http\Controllers\API\Guest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\ResourceRepository;
use App\Models\Resource;
use App\Http\Resources\ResourceResource;

class FeaturedResourceController extends Controller
{
    protected $repository;

    public function __construct(ResourceRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resources = $this->repository->indexForPublicFeatured(request(), currentUserType(), 10);

        return ResourceResource::collection($resources);
    }
}
