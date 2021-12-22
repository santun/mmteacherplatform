<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use Lcobucci\JWT\Parser;
use DB;

class LoginController extends Controller
{
	/*
    public function login (Request $request) 
	{
		if ($request->grant_type == 'password') { }
		$user = User::where('email', $request->email)->first();

		if ($user) {

			if (Hash::check($request->password, $user->password)) {
				$token = $user->createToken('Laravel Password Grant Client')->accessToken;
				$response = ['token' => $token];
				return response($response, 200);
			} else {
				$response = "Password missmatch";
				return response($response, 422);
			}

		} else {
			$response = 'User does not exist';
			return response($response, 422);
		}

	}
	*/
	
	public function logout (Request $request) 
	{
		$token = $request->user()->token();
		$token->revoke();

		$response = ['message' => 'You have been succesfully logged out!'];
		return response($response, 200);
	}
	
	/*
	Credit to : http://www.technitiate.com/logout-laravel-passport-authentication/
	public function logout(Request $request) 
	{
		$value = $request->bearerToken();
		
		if ($value) {

			$id = (new Parser())->parse($value)->getHeader('jti');
			$revoked = DB::table('oauth_access_tokens')->where('id', '=', $id)->update(['revoked' => 1]);
			//$this->guard()->logout();
		}
		
		Auth::logout();
		
		$response = 'You have been succesfully logged out!';
		
		return response($response, 200);
	}
	*/
}
