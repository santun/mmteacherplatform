<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use DB;

class ProfileController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id = null)
    {
        //return response()->json(auth()->user());
        return new UserResource(auth()->user());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'bail|required|alpha_dash|max:255|unique:users,id,' . auth()->user()->id,
            'email' => 'bail|required|nullable|email|max:255|unique:users,id,' . auth()->user()->id,
            'mobile_no' => 'required|min:9',
        ]);

        $user = auth()->user();
        $this->saveRecord($request, $user);

        //$token = $user->createToken('Laravel Password Grant Client')->accessToken;

        $token = $user->createToken('Laravel Password Grant Client');//($request->device_token);
        $response['access_token'] = $token->accessToken; //'Bearer ' . $token->accessToken;

        DB::table('oauth_access_tokens')->where('id', $token->token->id)->update(['expires_at' => now()->addDays(1)]);

        $response['data'] = new UserResource($user);
        //$response = ['message' => 'Your profile has been successfully updated.', new UserResource($user)];
        //return response(['access_token' => $token, 'data' => new UserResource($user)], 200);
        return response($response, 200);
    }

    public function saveRecord($request, $row)
    {
        $row->fill([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'mobile_no' => $request->mobile_no,
            'ec_college' => $request->ec_college,
            //'user_type' => $request->input('user_type'),
        ]);

        $row->user_type = $request->user_type;

        if ($request->input('subscribe_to_new_resources') !== null) {
            $row->subscribe_to_new_resources = $request->input('subscribe_to_new_resources');
        }

        if ($request->suitable_for_ec_year !== null) {
            if (is_array($request->suitable_for_ec_year)) {
                $row->suitable_for_ec_year = implode(',', $request->suitable_for_ec_year);
            }
        }

        if (is_array($request->notification_channel) && in_array('sms', $request->notification_channel)) {
            $row->notification_channel = 'sms';
        } else {
            $row->notification_channel = 'email';
        }

        $row->save();

        $row->subjects()->sync($request->subjects);

        if ($request->profile_image_in_baseb4) {
            //$row->addMediaFromBase64($request->profile_image_in_baseb4)->usingFileName(str_random(12))->toMediaCollection('profile');

            $image = 'data:image/png;base64,' . $request->profile_image_in_baseb4;

            $row->addMediaFromBase64($image)->usingFileName(str_random(12) . '.png')
                ->withCustomProperties(['file_extension' => 'png'])
                ->toMediaCollection('profile');
        }

        $response = ['message' => 'Your profile has been successfully updated.'];

        return response($response, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'bail|required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        if (!(Hash::check($request->get('current_password'), auth()->user()->password))) {
            // The passwords do not match
            return response(
                [
                    'errors' => 'Your current password does not matches with the password you provided. Please try again.'
                ],
                422
            );
        }
        if (strcmp($request->get('current_password'), $request->get('new_password')) == 0) {
            //Current password and new password are same
            return response(
                [
                    'errors' => 'New Password cannot be same as your current password. Please choose a different password.'
                ],
                422
            );
        }

        //Change Password
        $user = auth()->user();
        $user->password = bcrypt($request->get('new_password'));
        $user->save();

        $response = ['message' => 'New Password has been changed successfully!'];

        return response($response, 200);
    }

    public function cancel()
    {
        $user = auth()->user();
        $user->delete();

        // Delete all tokens
        \Laravel\Passport\Token::where('user_id', $user->id)->delete();

        $response = ['message' => 'Account cancelled.'];

        return response($response, 200);
    }
}
