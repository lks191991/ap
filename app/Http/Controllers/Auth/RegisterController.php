<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Helpers\SiteHelpers;
use App\Helpers\GeoPlugin;
use \App\Models\Student;
use \App\Models\Countries;
use \App\Models\SchoolCategory;
use App\Models\Course;
use App\Models\School;
use App\Models\Tutor;
use App\Models\Classes;
use Illuminate\Auth\Events\Registered;
use App\Mail\sendEmailtoNewuser;
use Illuminate\Support\Facades\Mail;
use Auth;
use Illuminate\Support\Str;


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
    //protected $redirectTo = RouteServiceProvider::HOME;

    /* Redirect to step 2 after register */
    protected $redirectTo = '/register';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => array()]);

        /* Below Auth middleware use check auth on it */
        //$this->middleware('auth', ['except' => array('showRegistrationForm', 'step1')]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
		if($data['register_as'] == 'student') {
			return Validator::make($data, [
						'first_name' => 'required|regex:/^[a-zA-Z_\-]*$/',
						'last_name' => 'nullable|regex:/^[a-zA-Z_\-]*$/',
						'password' => 'required|confirmed|string|min:6',
						'email' => 'email|max:255|unique:users',
						'mobile' => 'required|numeric|unique:students',
						'register_as' => 'required'
							], [
						'first_name.regex' => "First Name contains <li>The first name must contain alpha characters only</li>",
						'last_name.regex' => "Last Name contains <li>The last name must contain alpha characters only</li>",
						'register_as.required' => "Register as required.",
							// 'password.regex' => "Password contains <ul><li>At least one lowercase</li><li>At least one uppercase</li><li>At least one digit</li><li>At least one special character</li><li>At least it should have 8 characters long</li></ul>",
			]);
		} else  {
		
			return Validator::make($data, [
						'first_name' => 'required|regex:/^[a-zA-Z_\-]*$/',
						'last_name' => 'nullable|regex:/^[a-zA-Z_\-]*$/',
						'password' => 'required|confirmed|string|min:6',
						'email' => 'email|max:255|unique:users',
						'mobile' => 'required|numeric|unique:tutors',
						'register_as' => 'required'
							], [
						'first_name.regex' => "First Name contains <li>The first name must contain alpha characters only</li>",
						'last_name.regex' => "Last Name contains <li>The last name must contain alpha characters only</li>",
						'register_as.required' => "Register as required.",
							// 'password.regex' => "Password contains <ul><li>At least one lowercase</li><li>At least one uppercase</li><li>At least one digit</li><li>At least one special character</li><li>At least it should have 8 characters long</li></ul>",
			]);
		}
    }

    

    /**
     * Show registration froms according to step number.
     *
     * @param  int  $num
     * @return form view
     */
    public function showRegistrationForm($num = null)
    {
            return view('auth.register');
    }

    

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        $token = Str::random(64);
  
      
        $user = User::create([
                    'username' => $data['email'],
                    'email' => $data['email'],
                    'name' => $data['first_name']. " ".$data['last_name'],
                    'password' => Hash::make($data['password']),
                    'mobile' => $data['mobile'],
                    'token' => $token
        ]);
        
        //$user = User::find(2);

        $role = \DB::table('roles')->where('slug', '=', $data['register_as'])->first();  //choose the default role upon user creation.

        /* Below code for assign user role */
        $user->attachRole($role, $user->id);

        $register_as = $data['register_as'];
        if ($register_as == 'student') {
            /* Below code for save student data */
            $user->insertStudent($user, $data);
        }

        if ($register_as == 'tutor') {
            /* Below code for save tutor data */
            $user->insertTutor($user, $data);
        }

        /* Below code for send otp to student or  tutor */
        //SiteHelpers::updateOtp($user->id, $data['phone_code'], $data['mobile'], $data['otp']);        
        //$sendOtp = SiteHelpers::sendOtpToUser($data['phone_code'], $data['mobile'], $data['otp']);
        
        /* Send verification to student or  tutor using TWILo */
        //$vsid = SiteHelpers::sendOtpUsingTwilio($user->id, $data['phone_code'], $data['mobile']);
        
       // session(['vsid' => $vsid]);
        //session(['newCustomer' => 1]);
        session(['userId' => $user->id]);
        
        if (!empty($user->email))
        Mail::send('emails.emailVerificationEmail', ['token' => $token,'register_as' => $data['register_as']], function($message) use($data){
            $message->to($data['email']);
            $message->subject('Email Verification Mail');
        });

        return $user;
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
		$data = $request->all();
       $user = $this->create($request->all());

        $register_as = $data['register_as'];
        if ($register_as == 'student') {
            return redirect("/register")->with('success', 'You are successfully registered please login.');;
        }

        if ($register_as == 'tutor') {
            return redirect("/register")->with('success', 'You are successfully registered. Your detail is sent to admin once approved you can log in.');;
        }
        
        // return $this->registered($request, $user)
        //?: redirect($this->redirectPath());
    }

    
    public function redirectTo()
    {
        return $this->redirectTo;

        
    }

    /**
     * verify user by email vedrification link.
     *
     * @redirect to login
     */
      public function verifyUser($token)
    {
		$verifyUser = User::where("token", $token)->first();
        if (isset($verifyUser)) {
            if (empty($verifyUser->email_verified_at)) {
				if($verifyUser->userRole->role->slug=='tutor')
				{
					 DB::table('users')->where('token', $token)->update(['email_verified_at' => Carbon::now(),'token' => '']);
				}
				else
				{
					 DB::table('users')->where('token', $token)->update(['email_verified_at' => Carbon::now(),'token' => '','status' => 1]);
				}
               
                $status = "Your account is verified. You can now login.";
                if (!empty($verifyUser->email))
                Mail::to($verifyUser->email, "New registration on " . env('APP_NAME', ''))->send(new sendEmailtoNewuser($verifyUser, $verifyUser->name));
            } else {
                $status = "Your account is already verified. You can now login.";
            }
            return redirect('/login')->with('success', $status);
        } else {
            return redirect('/login')->with('error', "Sorry your verification token not valid.");
        }
    }
    

}
