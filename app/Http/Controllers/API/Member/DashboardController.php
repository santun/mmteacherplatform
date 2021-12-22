<?php

namespace App\Http\Controllers\API\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Resource;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $user = auth()->user();
        $notifications = $user->notifications()->take(5)->get();
        $totalResources = $user->resources()->where('approval_status', Resource::APPROVAL_STATUS_APPROVED)->count();
        $totalFavourites = $user->favourites()->count();
        $totalNotifications = $user->notifications->count();

        $showTotalResources = false;

        if ($user->isAdmin() || $user->isManager()) {
            $showTotalResources = true;
        }

        $json = [
            'data' => [
                'total_resources' => $totalResources,
                'total_favourites' => $totalFavourites,
                'total_notifications' => $totalNotifications,
                'flags' => [
                    'show_total_resources' => $showTotalResources
                ],
                'notificaitons' => $notifications,
            ]
        ];


        return response()->json($json);
    }
}
