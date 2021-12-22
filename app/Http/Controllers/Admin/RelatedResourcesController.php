<?php

namespace App\Http\Controllers\Admin;

// use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\RelatedResource;
use App\Http\Requests\RequestRelatedResource as Request;

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
        if ($id == $request->input('resource_id')) {
            return redirect()->route('admin.resource.related', $id)
            ->with('error', 'You cannot add the same resource to related resources.');
        }

        if (!$related = RelatedResource::where('resource_id', $id)
            ->where('related_resource_id', $request->input('resource_id'))
            ->first()) {
            $related = new RelatedResource();
            $related->resource_id = $id;
            $related->related_resource_id = $request->input('resource_id');
            $related->save();
        }

        return redirect()->route('admin.resource.related', $id)
        ->with('success', 'Successfully added to related resources.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if ($post = Resource::findOrFail($id)) {
            $related_posts = $post->related;
        }

        return view('backend.resource.related', compact('post', 'related_posts'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $resource_id)
    {
        if ($related = RelatedResource::where('resource_id', $id)
            ->where('related_resource_id', $resource_id)
            ->first()) {
            $related->delete();
        } else {
            return redirect()->route('admin.resource.related', $id)
            ->with('error', 'Invalid Resource IDs');
        }

        return redirect()->route('admin.resource.related', $id)
            ->with('success', 'Successfully removed from related resources.');
    }
}
