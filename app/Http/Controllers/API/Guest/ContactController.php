<?php

namespace App\Http\Controllers\API\Guest;

use App\Http\Requests\RequestContactBackend as Request;
use App\Models\Contact;
use App\Http\Controllers\Controller;
use App\Repositories\ContactRepository;

class ContactController extends Controller
{
    protected $repository;

    public function __construct(ContactRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\RequestContactBackend  $request     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validated();

        $this->repository->saveRecord($request);

        return response()->json(['message' => 'Your Message has been successfully sent to admin.']);
    }
}
