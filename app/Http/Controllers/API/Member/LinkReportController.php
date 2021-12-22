<?php

namespace App\Http\Controllers\API\Member;

use App\Models\LinkReport;
use App\Http\Controllers\Controller;
use App\Repositories\LinkReportRepository;
use App\Http\Requests\RequestLinkReport as Request;
use App\Http\Resources\LinkReportResource;

class LinkReportController extends Controller
{
    protected $repository;

    public function __construct(LinkReportRepository $repository)
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

        return new LinkReportResource($post);
    }
}
