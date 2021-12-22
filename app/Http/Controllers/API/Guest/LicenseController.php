<?php

namespace App\Http\Controllers\API\Guest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\LicenseRepository;
use App\Models\License;

class LicenseController extends Controller
{
    protected $repository;

    public function __construct(LicenseRepository $repository)
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
        // $posts = $this->repository->publishedOnly(request());
        $posts = License::select('id', 'title')->get();

        return response()->json($posts);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        if (!$post = $this->repository->findById($slug)) {
            return response()->json(['message' => 'Resource Not Found'], 404);
        }

        return response()->json($post);
    }
}
