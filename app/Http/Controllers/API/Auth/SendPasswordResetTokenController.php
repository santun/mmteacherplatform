<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Notifications\ResetTokenMobile;
use DB;
use Auth;
use Hash;
use Carbon\Carbon;
use App\User;
use App\Jobs\SendResetTokenSMS;

class SendPasswordResetTokenController extends Controller
{
	/*
	* (Email)
	*/
    public function sendEmailPasswordResetToken(Request $request)
    {
        $rules = [
            'email' => 'required|string|email|max:255',
        ];

        $request->validate($rules);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
			return response(['errors' => "We can't find a user with this email address."], 422);
        }

        //create a new token to be sent to the user.
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => rand(100000, 1000000),//str_random(60), //change 60 to any length you want
            'created_at' => Carbon::now()
        ]);

        $tokenData = DB::table('password_resets')->where('email', $request->email)->first();

        $token = $tokenData->token;

        //$email = $request->email; // or $email = $tokenData->email;

        /**
        * Send email to the email above with a link to your password reset
        * something like url('password-reset/' . $token)
        * Sending email varies according to your Laravel version. Very easy to implement
        */
        $user->notify(new ResetTokenMobile($token));

        $response = ['message' => "We have e-mailed Reset Token Number to reset your account's password!"];

        return response($response, 200);
    }

    /*
    * (SMS/Email)
    * Credit to : https://stackoverflow.com/questions/42229086/how-to-create-a-password-reset-method-in-laravel-when-using-the-database-user-pr
    */
    public function sendPasswordResetToken(Request $request)
    {
        $rules = [
            'email' => 'required|string|email|max:255',
            'mobile_no' => 'required|string|max:255',
        ];

        $request->validate($rules);

        $user = User::where('email', $request->email)->where('mobile_no', $request->mobile_no)->first();
        if (!$user) {
            return response(['errors' => "We can't find a user with these credentials."], 422);
        }

        //create a new token to be sent to the user.
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'mobile_no' => $request->mobile_no,
            'token' => rand(100000, 1000000), //change 60 to any length you want
            'created_at' => Carbon::now()
        ]);

        $tokenData = DB::table('password_resets')->where('email', $request->email)->where('mobile_no', $request->mobile_no)->first();

        $token = $tokenData->token;

        $email = $request->email; // or $email = $tokenData->email;

        /**
        * Send email to the email above with a link to your password reset
        * something like url('password-reset/' . $token)
        * Sending email varies according to your Laravel version. Very easy to implement
        */
        $user->notify(new ResetTokenMobile($token));

        //SMS
        # Add job to Queue to send SMS
        $job = (new SendResetTokenSMS($user))->delay(now()->addSeconds(1));

        //dd($job);
        dispatch($job);

        $response = ['message' => 'Please copy and paste Reset Token Number that we sent to your email and mobile phone in the text box.'];

		return response($response, 200);
    }

    public function verifyToken(Request $request)
    {
        $rules = [
            'token' => 'required|string|max:255',
        ];

        $request->validate($rules);

        $tokenData = DB::table('password_resets')->where('token', $request->token)->first();

        if (!$tokenData) {
            return response(['errors' => "Invalid Token."], 422);
        }

        $response = ['token' => $request->token, 'message' => 'Valid Token. Please use it with reset password form.'];

        return response($response, 200);
    }

    public function resetPassword(Request $request)
    {
        //some validation
        $rules = [
            'email' => 'required|string|email|max:255',
            //'mobile_no' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'token' => 'required|string|max:255',
        ];

        $request->validate($rules);

        $tokenData = DB::table('password_resets')->where('email', $request->email)->where('token', $request->token)->first();
        if (!$tokenData) {
            return response(['errors' => "The e-mail address you want to reset is wrong."], 422);
        }

        $user = User::where('email', $tokenData->email)->first();
        if (!$user) {
            return redirect()->back()->with('error', "We can't find a user with that e-mail address.");
        } //or wherever you want

        $user->password = Hash::make($request->password);
        $user->update(); //or $user->save();


        // If the user shouldn't reuse the token later, delete the token
        DB::table('password_resets')->where('email', $user->email)->delete();

        //do we log the user directly or let them login and try their password for the first time ? if yes
        // Auth::login($user);

        $response = ['message' => "Your account's password has been successfully reset."];

        return response($response, 200);
    }
}
