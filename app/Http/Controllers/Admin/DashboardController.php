<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\Article;

class DashBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resources = Resource::latest()
                     ->limit(10)
                     ->get();

        $articles = Article::latest()
                     ->limit(10)
                     ->get();

        return view('backend.dashboard.index', compact('resources', 'articles'));
    }
}
