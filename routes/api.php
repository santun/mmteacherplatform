<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
 * Public API Routes
 */

require(base_path().'/routes/api/guest.php');
require(base_path().'/routes/api/member.php');

#Register
Route::post('register', 'API\Auth\RegisterController@register')->name('register.api');
#Verification
Route::post('verify/post_otp', 'API\Auth\VerifyOTPController@verifyOTP')->name('auth.verify.post_otp.api');
#Resend OTP
Route::post('resend/otp', 'API\Auth\VerifyOTPController@resendOTP')->name('auth.resend.otp.api');
#Forgot Password
//Route::post('forgot/password', 'API\Auth\ForgotPasswordController')->name('forgot.password.api');
Route::post('reset-password/email/send_reset_token', 'API\Auth\SendPasswordResetTokenController@sendEmailPasswordResetToken')->name('auth.reset-password.email.send_reset_token.api');
Route::post('reset-password/send_reset_token', 'API\Auth\SendPasswordResetTokenController@sendPasswordResetToken')->name('auth.reset-password.send_reset_token.api');
Route::post('reset-password/verify-token', 'API\Auth\SendPasswordResetTokenController@verifyToken')->name('auth.reset-password.verify-token.api');
Route::post('reset-password', 'API\Auth\SendPasswordResetTokenController@resetPassword')->name('auth.post.reset-password.api');

/*
 * Private API Routes
 */

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->namespace('API')->name('api.')->group(function () {
    Route::get('me', 'ProfileController@show');
    Route::get('me/cancel-account', 'ProfileController@cancel');
    Route::post('change-password', 'ProfileController@updatePassword')->name('change-password.update');
    Route::post('update-profile', 'ProfileController@update')->name('profile.update');
    Route::get('logout', 'Auth\LoginController@logout');


    //Route::apiResource('page', 'PageController');
    //Route::apiResource('resource', 'ResourceController');
});

Route::get('keywords', function (Request $request) {
    $q = $request->input('q');

    $commodities = \App\Models\Keyword::where('keyword', 'LIKE', "%{$q}%")
      ->select('id', 'keyword')
      ->paginate();

    $formatted_tags = [];
    foreach ($commodities as $tag) {
        $formatted_tags[] = ['id' => $tag->keyword, 'text' => $tag->keyword];
    }

    return response()->json($formatted_tags);
});
