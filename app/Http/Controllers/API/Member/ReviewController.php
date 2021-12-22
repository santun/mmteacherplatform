<?php

namespace App\Http\Controllers\API\Member;

use App\Models\Review;
use App\Models\Resource;
use App\Http\Controllers\Controller;
use App\Repositories\ReviewRepository;
use App\Http\Requests\RequestReview as Request;
use App\Http\Resources\ReviewResource;

class ReviewController extends Controller
{
    protected $repository;

    public function __construct(ReviewRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\RequestReview $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $resourceId)
    {
        $validated = $request->validated();

        $post = $this->repository->saveRecord($request);

        return new ReviewResource($post);
    }
}
