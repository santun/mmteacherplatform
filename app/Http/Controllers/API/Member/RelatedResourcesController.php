<?php

namespace App\Http\Controllers\API\Member;

// use Illuminate\Http\Request;
use App\Http\Requests\RequestRelatedResource as Request;
use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\RelatedResource;
use App\Http\Resources\RelatedResourceResource;
use App\Http\Resources\ResourceResource;

class RelatedResourcesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(int $id, Request $request)
    {
        if (!$related = RelatedResource::where('resource_id', $id)
            ->where('related_resource_id', $request->input('resource_id'))
            ->first()) {
            $related = new RelatedResource();
            $related->resource_id = $id;
            $related->related_resource_id = $request->input('resource_id');
            $related->save();
        }

        return response()->json(['message' => 'Successfully added the related resource.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $related_posts = null;
        if ($post = Resource::findOrFail($id)) {
            $related_posts = $post->related;
        }

        return RelatedResourceResource::collection($related_posts);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($resource_id, $related_resource_id)
    {
        if ($related = RelatedResource::where('resource_id', $resource_id)
            ->where('related_resource_id', $related_resource_id)
            ->first()) {
            $related->delete();
        } else {
            return response()->json(['message' => 'Invalid Resource IDs']);
        }

        return response()->json(['message' => 'Successfully deleted the related resource.']);
    }
}
