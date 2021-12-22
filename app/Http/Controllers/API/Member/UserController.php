<?php

namespace App\Http\Controllers\API\Member;

use App\Models\Resource;
use App\User;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Http\Requests\RequestUserApproval as Request;
use App\Http\Requests\RequestUserUpdate;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    protected $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
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
            $posts = $this->repository->index(request());
        } elseif ($user_type == User::TYPE_MANAGER) {
            $posts = $this->repository->indexForManager(request());
        } elseif ($user_type == User::TYPE_TEACHER_EDUCATOR) {
            $posts = $this->repository->indexForTeacherEducator(request());
        }

        if ($posts) {
            return UserResource::collection($posts);
        }

        return response()->json(['message' => 'There are no users.', 'data' => null]);
    }

    /**
     * Update the specified user in storage.
     *
     * @param  App\Http\Requests\RequestUserApproval $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id, $action)
    {
        $text = $this->repository->updateStatus($id, $action);

        return response()->json(['message' => 'User has been updated']);
    }

    /**
     * Update User
     *
     * @param RequestUserUpdate $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(RequestUserUpdate $request, $id)
    {
        $post = User::findOrFail($id);

        $post->user_type = $request->input('user_type');
        $post->ec_college = $request->input('ec_college');
        $post->save();

        return response()->json(['message' => 'User has been successfully updated.']);
    }
}
