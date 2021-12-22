<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\User;

class RequestUserApproval extends FormRequest
{
    protected $message;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = auth()->user();

        $targetUser = User::findOrFail($this->route('id'));

        if ($targetUser->type == User::TYPE_ADMIN) {
            $this->message = 'You can not update approval status for admin users.';
            return false;
        }

        if ($user->id == $targetUser->id) {
            $this->message = 'You can not update approval status for yourself.';
            return false;
        }

        if ($user->type == User::TYPE_MANAGER && $user->ec_college != $targetUser->ec_college) {
            $this->message = 'You can not update approval status for users from Education College .';
            return false;
        }

        if (!in_array($this->route('action'), ['undo', 'approve', 'block'])) {
            $this->message = 'Invalid Action! Only undo, approve and block are allowed.';
            return false;
        }

        return true;
    }


    /**
     * Inject GET parameter "type" into validation data
     *
     * @param array $keys Properties to only return
     *
     * @return array
     */
    public function all($keys = null)
    {
        $data = parent::all($keys);
        $data['user_id'] = $this->route('id');
        return $data;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    protected function failedAuthorization()
    {
        throw new \Illuminate\Auth\Access\AuthorizationException($this->message);
    }
}
