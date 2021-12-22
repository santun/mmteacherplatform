<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ResourceRepository;
use App\Repositories\ArticleRepository;
use App\Repositories\SlideRepository;

class HomeController extends Controller
{
    protected $user_type;
    protected $resources;
    protected $articles;
    protected $slide;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ResourceRepository $resource, ArticleRepository $article, SlideRepository $slide)
    {
        $this->resource = $resource;
        $this->article = $article;
        $this->slide = $slide;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->user_type = currentUserType();
        $resources = $this->resource->indexForPublicFeatured(request(), $this->user_type, 9);
        $slides = $this->slide->getPublishedSlides(5);
        $articles = $this->article->getPublishedArticles();

        return view('frontend.home.index', compact('articles', 'resources', 'slides'));
    }
}
