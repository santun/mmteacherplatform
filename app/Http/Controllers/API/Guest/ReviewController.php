<?php

namespace App\Http\Controllers\API\Guest;

use App\Models\Review;
use App\Models\Resource;
use App\Http\Controllers\Controller;
use App\Repositories\ReviewRepository;
use App\Http\Resources\ReviewResource;

class ReviewController extends Controller
{
    protected $repository;

    public function __construct(ReviewRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index($resourceId)
    {
        if (!$resource = Resource::find($resourceId)) {
            return response()->json(['message' => 'Invalid Resource ID']);
        }

        $reviews = $resource->reviews()->paginate();

        return ReviewResource::collection($reviews);
    }
}
