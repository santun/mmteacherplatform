<?php

namespace App\Http\Controllers\API\Member;

use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = auth()->user()->notifications()->paginate();

        return response()->json($notifications);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();

        if ($notification != null) {
            $notification->markAsRead();
        }

        return response()->json($notification);
    }

    public function updateStatus($id, $action)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();

        if ($action == 'mark-as-read') {
            $notification->markAsRead();
        } elseif ($action == 'mark-as-unread') {
            $notification->read_at = null;
            $notification->save();
        }

        return response()->json(['status' => 'success', 'data' => $notification]);
    }

    /**
     * Destroy the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();

        if (!$notification) {
            return response()->json(['status' => 'error', 'message' => 'Invalid Notification.']);
        }

        $notification->delete();

        return response()->json(['status' => 'success', 'message' => 'Successfully deleted.']);
    }
}
