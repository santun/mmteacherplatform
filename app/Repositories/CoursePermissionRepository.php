<?php

namespace App\Repositories;

use App\Models\Course;
use App\User;

class CoursePermissionRepository
{
    /**
     * Check if user can edit the resource
     *
     * @param App\Models\Resource $course
     * @return boolean
     */
    public static function canEdit($course)
    {
        if (!$user = auth()->user()) {
            return false;
        }
        
        if ($user->isAdmin() || ($user->isManager() && $course->is_locked != 1)
            || ($user->id == $course->user_id && $course->allow_edit == 1 && $course->is_locked != 1)) {
            return true;
        }

        return false;
    }

    /**
     * Check if user can approve the resource
     *
     * @param App\Models\Resource $course
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
     * @param App\Models\Resource $course
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
     * @param App\Models\Resource $course
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
