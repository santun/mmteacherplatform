<?php

namespace App\Repositories;

use App\User;
use App\Models\College;
use Illuminate\Http\Request;
use Exception;

class UserRepository
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public static function getUsers($types)
    {
        return User::whereIn('type', $types)->get();
    }

    public static function getAdminAndManager()
    {
        return self::getUsers([User::TYPE_ADMIN, User::TYPE_MANAGER]);
    }

    public static function getActiveUsers()
    {
        return User::where('approved', User::APPROVAL_STATUS_APPROVED)
                    ->where('verified', 1)
                    ->where('blocked', '!=', 1)
                ->get();
    }

    /**
     * Get the list of Student Teacher who subscribe to new resources
     *
     * @return void
     */
    public static function getStudentTeachers()
    {
        return User::where('approved', User::APPROVAL_STATUS_APPROVED)
                    ->where('verified', 1)
                    ->where('subscribe_to_new_resources', 1)
                    ->where('type', User::TYPE_STUDENT_TEACHER)
                    ->where('blocked', '!=', 1)
                ->get();
    }

    public static function getAdminAndManagerOfSameCollege($user)
    {
        $types = [User::TYPE_ADMIN, User::TYPE_MANAGER];

        return User::where('type', User::TYPE_ADMIN)
                    ->orWhere(function ($query) use ($user) {
                        $query->where('type', User::TYPE_MANAGER)
                            ->where('ec_college', '=', $user->ec_college);
                    })
                ->get();
    }

    /**
     * Get users who have resource upload permissions
     */
    public static function getAllUploaders()
    {
        $types = [User::TYPE_ADMIN, User::TYPE_MANAGER, User::TYPE_TEACHER_EDUCATOR];

        return User::select('id', 'name')
            ->whereIn('type', $types)
            ->orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');
    }

    /**
     * Get users who have resource upload permissions
     */
    public static function getAllUploadersFromSameCollege()
    {
        $types = [User::TYPE_ADMIN, User::TYPE_MANAGER, User::TYPE_TEACHER_EDUCATOR];

        if (auth()->user()->ec_college) {
            return User::select('id', 'name')
            ->whereIn('type', $types)
            ->where('ec_college', auth()->user()->ec_college)
            ->orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');
        }

        return null;
    }

    public static function getTypes($includingGuest = true)
    {
        $types = [
            User::TYPE_ADMIN => User::TYPE_ADMIN,
            User::TYPE_MANAGER => User::TYPE_MANAGER,
            User::TYPE_TEACHER_EDUCATOR => User::TYPE_TEACHER_EDUCATOR,
            User::TYPE_STUDENT_TEACHER => User::TYPE_STUDENT_TEACHER,
        ];

        if ($includingGuest) {
            $types[User::TYPE_GUEST] = User::TYPE_GUEST;
        }

        $types = ['' => '- Select Accessible Right -'] + $types;

        return $types;
    }

    public static function getTypesList($includingGuest = true)
    {
        $types = [
            User::TYPE_ADMIN => User::TYPE_ADMIN,
            User::TYPE_MANAGER => User::TYPE_MANAGER,
            User::TYPE_TEACHER_EDUCATOR => User::TYPE_TEACHER_EDUCATOR,
            User::TYPE_STUDENT_TEACHER => User::TYPE_STUDENT_TEACHER,
        ];

        if ($includingGuest) {
            $types[User::TYPE_GUEST] = User::TYPE_GUEST;
        }

        return $types;
    }

    public static function getUserTypes($includePlaceHolder = true)
    {
        $types = [
            User::TYPE_EDUCATION_STAFF => User::VALUE_EDUCATION_STAFF,
            User::TYPE_COLLEGE_TEACHING_STAFF => User::VALUE_COLLEGE_TEACHING_STAFF,
            User::TYPE_COLLEGE_NON_TEACHING_STAFF => User::VALUE_COLLEGE_NON_TEACHING_STAFF,
            User::TYPE_COLLEGE_STUDENT_TEACHER => User::VALUE_COLLEGE_STUDENT_TEACHER,

            //User::TYPE_EDUCATION_STAFF => User::VALUE_EDUCATION_STAFF,
            //User::TYPE_COLLEGE_STUDENT_TEACHER => User::VALUE_COLLEGE_STUDENT_TEACHER,
            //User::TYPE_STAFF => User::VALUE_STAFF,

            //User::TYPE_NON_TEACHING_STAFF => User::VALUE_NON_TEACHING_STAFF,
            //User::TYPE_COLLEGE_TEACHING_STAFF => User::VALUE_COLLEGE_TEACHING_STAFF,
        ];

        if ($includePlaceHolder) {
            $types = ['' => '- Select Type of Users -'] + $types;
        }

        return $types;
    }

    public static function getShortName($name)
    {
        $text = $name;
        preg_match_all('/\b\w/', $name, $match);

        if (is_array($match[0])) {
            $text = implode('', $match[0]);
        }

        return $text;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts = $this->model
                        ->with(['media', 'subjects', 'college'])
                        ->withSearch($request->input('search'))
                        ->withApproved($request->input('approved'))
                        ->sortable(['updated_at' => 'desc'])
                        ->paginate($request->input('limit'));

        $posts->appends($request->all());

        return $posts;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexForManager(Request $request)
    {
        $user = auth()->user();

        if ($college = College::find($user->ec_college)) {
            $posts = $this->model
                        ->with(['media', 'subjects', 'college'])
                        ->where('ec_college', '=', $user->ec_college)
                        ->withSearch($request->input('search'))
                        ->withoutMe()
                        ->withApproved($request->input('approved'))
                        ->sortable(['updated_at' => 'desc'])
                        ->paginate($request->input('limit'));

            $posts->appends($request->all());

            return $posts;
        }

        return null;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexForTeacherEducator(Request $request)
    {
        $user = auth()->user();

        if ($college = College::find($user->ec_college)) {
            $posts = $this->model
                        ->with(['media', 'subjects', 'college'])
                        ->withType(User::TYPE_STUDENT_TEACHER)
                        ->where('ec_college', '=', $user->ec_college)
                        ->withSearch($request->input('search'))
                        ->withoutMe()
                        ->withApproved($request->input('approved'))
                        ->sortable(['updated_at' => 'desc'])
                        ->paginate($request->input('limit'));

            $posts->appends($request->all());

            return $posts;
        }

        return null;
    }

    public function updateStatus($id, $action)
    {
        $post = User::findOrFail($id);

        // $this->isValidToApprove($id);

        if ($action == 'approve') {
            $post->approved = User::APPROVAL_STATUS_APPROVED;
            //$post->approved_by = auth()->user()->id;
            $text = 'approved';
        } elseif ($action == 'undo') {
            $post->approved = User::APPROVAL_STATUS_PENDING;
            $text = 'undo';
        } else {
            $post->approved = User::APPROVAL_STATUS_BLOCKED;
            $text = 'blocked';
        }

        $post->save();

        /*         // notify to admin and manager users
                if ($action == 'approve' || $action == 'reject') {
                    $users = UserRepository::getAdminAndManager();

                    Notification::send($users, new ResourceApprovalUpdated($post, $text));

                    // notify to submitted user
                    Notification::send($post->user, new ResourceApprovalUpdated($post, $text));
                } */

        return $text;
    }

    public function isValidToApprove($user_id)
    {
        $user = auth()->user();

        $targetUser = User::findOrFail($user_id);

        if ($user->id == $targetUser->id) {
            return redirect()->route('member.user.index')
            ->with('error', 'You can not update approval status for yourself.');
            throw new Exception('You can not update approval status for yourself.');
        }

        if ($user->type != User::TYPE_ADMIN) {
            throw new Exception('You can not update approval status for admin users.');
        }

        return true;
    }
}
