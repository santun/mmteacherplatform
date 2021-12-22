<?php

namespace App\Repositories;

use App\Models\Resource;
use App\User;

class ResourcePermissionRepository
{
    /**
     * Check if user can edit the resource
     *
     * @param App\Models\Resource $resource
     * @return boolean
     */
    public static function canEdit($resource)
    {
        if (!$user = auth()->user()) {
            return false;
        }

        if ($user->isAdmin() || ($user->isManager() && $resource->is_locked != 1)
            || ($user->id == $resource->user_id && $resource->allow_edit == 1 && $resource->is_locked != 1)) {
            return true;
        }

        return false;
    }

    /**
     * Check if user can approve the resource
     *
     * @param App\Models\Resource $resource
     * @return boolean
     */
    public static function canApprove()
    {
        $user = auth()->user();

        if ($user->isAdmin() || $user->isManager()) {
            return true;
        }

        return false;
    }

    /**
     * Check if user can lock the resource
     *
     * @param App\Models\Resource $resource
     * @return boolean
     */
    public static function canLock()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Check if user can publish the resource
     *
     * @param App\Models\Resource $resource
     * @return boolean
     */
    public static function canPublish()
    {
        $user = auth()->user();

        if ($user->isAdmin() || $user->isManager()) {
            return true;
        }

        return false;
    }
}
