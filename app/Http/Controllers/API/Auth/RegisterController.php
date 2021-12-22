<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Notifications\UserRegisteredMobile;
use App\Jobs\SendOTPSMS;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'username' => 'required|alpha_dash|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile_no' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'profile_image' => 'image|mimes:jpeg,jpg,png,bmp,gif,svg',
            'notification_channel' => 'required',
        ];

        $request->validate($rules);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'mobile_no' => $request->mobile_no,
            'password' => Hash::make($request->password),
            'ec_college' => $request->ec_college,
            //'user_type' => $data['user_type'],
        ]);

        //$user = User::create($request->toArray());
        $user->user_type = $request->user_type;

        if ($request->suitable_for_ec_year !== null) {
            $user->suitable_for_ec_year = implode(',', $request->suitable_for_ec_year);
        }

        if (in_array('sms', $request->notification_channel)) {
            $user->notification_channel = 'sms';
        } else {
            $user->notification_channel = 'email';
        }

        $user->verification_code = rand(100000, 1000000);//str_random(6); mt_rand(100000,1000000)
        $user->save();

        //$user->subjects()->detach();
        $user->subjects()->sync($request->subjects);

        if ($request->profile_image_in_baseb4) {
            //$user->addMediaFromBase64($request->profile_image_in_baseb4)->usingFileName(str_random(12))->toMediaCollection('profile');

            $image = 'data:image/png;base64,' . $request->profile_image_in_baseb4;

            $user->addMediaFromBase64($image)->usingFileName(str_random(12) . '.png')
                ->withCustomProperties(['file_extension' => 'png'])
                ->toMediaCollection('profile');
        }

        // Send SMS or email
        $user->notify(new UserRegisteredMobile($user));

        if (in_array('sms', $request->notification_channel)) {
            // Add job to Queue to send SMS
            $job = (new SendOTPSMS($user))->delay(now()->addSeconds(1));

            dispatch($job);
        }

        if (!$user) {
            return response(['errors' => "We can't create a new user account."], 400);
        }

        //$token = $user->createToken('Laravel Password Grant Client')->accessToken;
        //$response = ['token' => $token, 'message' => 'Your account has been created, but it must go through an approval process.'];
        $response = ['message' => 'Your account has been created. Please verify it with the verification code that we sent to your email and mobile phone.'];

        return response($response, 201);
    }
}
