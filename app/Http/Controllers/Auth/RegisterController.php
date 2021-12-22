<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Notifications\UserRegistered;
use App\Jobs\SendOTPSMS;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = 'verify/get_otp'; //'/dashboard';

    protected $request;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'username' => 'required|alpha_dash|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile_no' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            //'suitable_for_ec_year' => 'required',
            'profile_image' => 'image|mimes:jpeg,jpg,png,bmp,gif,svg',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'mobile_no' => $data['mobile_no'],
            'password' => Hash::make($data['password']),
            // 'ec_college' => $data['ec_college'],
            //'user_type' => $data['user_type'],
        ]);

        if (in_array('sms', $data['notification_channel'])) {
            $user->notification_channel = 'sms';
        } else {
            $user->notification_channel = 'email';
        }

        $user->verification_code = rand(100000, 1000000);//str_random(6); mt_rand(100000,1000000)
        $user->user_type = $data['user_type'];

        if (isset($data['suitable_for_ec_year']) && $data['suitable_for_ec_year'] !== null) {
            $user->suitable_for_ec_year = implode(',', $data['suitable_for_ec_year']);
        }
        //$user->suitable_for_ec_year = $data['year_id'];

        // #README - default user type for all public registration
        $user->type = User::TYPE_STUDENT_TEACHER;

        $user->save();

        if (isset($data['subjects'])) {
            $user->subjects()->detach();
            $user->subjects()->attach($data['subjects']);
        }

        if ($this->request->profile_image) {
            $user->addMediaFromRequest('profile_image')->withCustomProperties(['file_extension' => $this->request->profile_image->extension()])->toMediaCollection('profile');
        }

        // Send SMS or email

        $data['email'] = $user->email;
        $data['name'] = $user->name;
        $data['mobile_no'] = $user->mobile_no;
        $data['otp'] = $user->verification_code;

        if (in_array('sms', $data['notification_channel'])) {
            // Add job to Queue to send SMS
            $job = (new SendOTPSMS($user))->delay(now()->addSeconds(1));

            //dd($job);
            dispatch($job);
        } else {
            /*
            Mail::send('emails.registerWithPromocode', $data, function($message) use ($data)
            {
                $message->from( env('MAIL_USERNAME'), env('APP_NAME') );
                $message->to( $data['email'], $data['name'] )
                    ->subject('Please verify your account!');

            });
            */
            //$user->notify(new UserRegistered($user));
        }

        $user->notify(new UserRegistered($user));

        return $user;
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        // $this->guard()->login($user);

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }
}
