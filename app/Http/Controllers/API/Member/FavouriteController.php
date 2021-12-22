<?php

namespace App\Http\Controllers\API\Member;

use Illuminate\Http\Request;
use App\Models\Resource;
use App\Models\Favourite;
use App\Http\Controllers\Controller;
use App\Http\Resources\ResourceResource;
use App\Notifications\ResourceLiked;
use Notification;

class FavouriteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = auth()->user()->favourites()->paginate();

        return ResourceResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\RequestReview $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $resourceId)
    {
        $resource = Resource::findOrFail($resourceId);

        auth()->user()->favourites()->syncWithoutDetaching([$resourceId]);

        /*
        $favourite = new Favourite();
        $favourite->user_id = auth()->user()->id;
        $favourite->resource_id = $resourceId;
        $favourite->save();
        */

        $favourite = Favourite::where('user_id', auth()->user()->id)
            ->where('resource_id', $resourceId)
            ->first();

        // Notified to resource's owner
        Notification::send($resource->user, new ResourceLiked($resource, $favourite));

        return response()->json(['message' => $resource->title. ' has been successfully added to Favourites.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($resourceId)
    {
        $resource = Resource::findOrFail($resourceId);

        auth()->user()->favourites()->detach($resourceId);

        if (request()->ajax()) {
            return response()->json(['message' => 'success']);
        }

        return response()
            ->json(
                [
                    'message' => $resource->title. ' has been successfully removed from Favourites.'
                ]
            );
    }
}
