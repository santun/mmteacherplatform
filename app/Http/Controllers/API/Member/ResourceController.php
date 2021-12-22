<?php

namespace App\Http\Controllers\API\Member;

use App\Models\Resource;
use App\User;
use App\Http\Controllers\Controller;
use App\Repositories\ResourceRepository;
use App\Http\Requests\RequestResource as Request;
use App\Http\Resources\ResourceResource;

class ResourceController extends Controller
{
    protected $resource;

    public function __construct(ResourceRepository $resource)
    {
        $this->resource = $resource;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_type = currentUserType();

        if ($user_type == User::TYPE_ADMIN) {
            $posts = $this->resource->index(request());
        } elseif ($user_type == User::TYPE_MANAGER) {
            $posts = $this->resource->indexForManager(request());
        } else {
            $posts = $this->resource->indexForMember(request());
        }

        return ResourceResource::collection($posts);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Resource::where('id', $id)->first();

        if (!$post) {
            return response()->json(['message' => 'Resource Not Found'], 404);
        }

        return new ResourceResource($post);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validated();

        $this->resource->saveRecord($request);

        $id = $this->resource->getKeyId();

        // $json = ['data' => $this->resource->getModel(), 'message' => 'Resource has been successfully saved.'];

        return new ResourceResource($this->resource->getModel());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validated();

        $this->resource->saveRecord($request, $id);

        // $json = ['data' => $this->resource->getModel(), 'message' => 'Resource has been successfully updated.'];

        return new ResourceResource($this->resource->getModel());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = $this->resource->find($id);

        $post->delete();

        $json = ['message' => 'Successfully deleted'];

        return response()->json($json);
    }

    public function clone($id)
    {
        $clone = $this->resource->cloneResource($id);

        return new ResourceResource($clone);
    }
}
