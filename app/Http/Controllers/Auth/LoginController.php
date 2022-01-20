<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Validator;
use DB;
use Illuminate\Support\Facades\Mail;
use Auth;
use App\User;
use App\Helpers\SiteHelpers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers {
    logout as performLogout;
	}

	public function logout(Request $request)
	{
		$this->performLogout($request);
		return redirect('/login');
	}
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');		
    }
    
    public function loginOTPView()
    {
        return view('auth.loginwithotp');
    }

    public function contactLoginOtp(Request $request)
	{
		$data = $request->all();
			$mobile = $data['mobile'];
		
		
		$otp = mt_rand(100000, 999999);
		$user = DB::table("users")->where("mobile",$mobile)->get()->count();
		
		if($user > 0)
		{
			
		$user_otp = DB::table("user_otp")->where("mobile",$mobile)->count();
		if($user_otp == 0)
		{
		DB::table('user_otp')->insert(
		['mobile' => $mobile, 'otp' => $otp]
		);
		}
		else
		{
			DB::table('user_otp')
			 ->where('mobile',$mobile)
			 ->update(
				['otp' => $otp]
			);
		}
		
		 session(['userMobile' => $mobile]);
         $message = "Your Login OTP is ".$otp;
		$sendOtp = SiteHelpers::sendOtp($mobile, $message);
      
		return response()->json([
        "status"=>200,
        "message"=>"OTP sent successfully.",
        'data'=>[
		"mobile" => $mobile
        ]
        ]); 
		
		}
		else
		{
			
		return response()->json([
        "status"=>401,
        "message"=>"your Contact number is wrong.",
        'data' => [
		"mobile" => $mobile
        ]
        ]); 
		}
		
		
	}

    public function contactLoginOtpVerify(Request $request)
	{
		$data = $request->all();
		$mobile = session()->get('userMobile');
		
		
		$otp = $data['otp'];
		$user_otp = DB::table("user_otp")->where("mobile",$mobile)->where("otp",$otp)->count();
		if($user_otp > 0)
		{
			DB::table("user_otp")->where("mobile",$mobile)->where("otp",$otp)->delete();
            $user = User::where("mobile",$mobile)->first();
            $user->mobile_verified_at =  Carbon::now();
            $user->save();
            auth()->login($user);
			session()->forget('userMobile');
			return response()->json([
                "status"=>200,
                "message"=>"OTP verified successfully.",
                'data'=>[
                "mobile" => $mobile
                ]
                ]); 
			
		}
		else
		{
            return response()->json([
                "status"=>401,
                "message"=>"OTP is incorrect.",
                'data' => [
                "mobile" => $mobile
                ]
                ]); 
		}
	}
		



    public function loginWithOtp(Request $request)
	{
        $login = request()->input('username');

        if(is_numeric($login)){
            $field = 'mobile';
        } elseif (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        } 
        else{
            return redirect()->route('login')->with('error','your email or username are wrong.');
        }

        $validator = Validator::make($request->all(), [
			$field => 'required|max:255',
		],[
		]);

        if ($validator->fails()) {
            return redirect('login')->withErrors($validator)->withInput();
        }

        
            $user = User::select('email','mobile')->where($field, $login)->first();
            print_r($user);
            exit;
       
        if(isset($user))
        {
            $user = Student::select('email')->where($field, $login)->first();
        }
       
        
    }

	public function loginView()
    {
        return view('auth.login');
    }
	
    
	public function login(Request $request)
	{
		
		$validator = Validator::make($request->all(), [
			'email' => 'required|string|min:4|max:255|email',
			'password' => 'required|min:6',
		],[
		]);
        $data = $request->all();
        if ($validator->fails()) {
            return redirect('login')->withErrors($validator)->withInput();
        }
		
		$remember_me = $request->has('remember_me') ? true : false; 
		$user = User::where("email", $request->input('email'))->first();
		
        if(isset($user))
        {
            if($user->status == 0 && $user->email_verified_at !== NULL) {
                return redirect()->route('login')->with('error','Your account is deactivated. Please request admin for activate it.');
            } else if($user->email_verified_at === NULL) {
                Mail::send('emails.emailVerificationEmail', ['token' => $user['token'],'register_as' => $user['name']], function($message) use($user){
                    $message->to($user['email']);
                    $message->subject('Email Verification Mail');
                });
                
                return redirect()->route('login')->with('error','Your email not verified please check your inbox and verify your email.');
            }
            else
            {
                if (auth()->attempt(['email' => $request->input('email'), 'password' => $request->input('password')], $remember_me))
                {
                    if (\Auth::user()->hasRole(['student','tutor'])) {
                        if (\Auth::user()->profile_completed==1) {
                            return redirect()->route('frontend.profile')->with('success','You have successfully logged in');

                        }else{
                            return redirect()->route('frontend.profile.first')->with('success','You have successfully logged in');
                        }
                    }
                    else{
                        return $this->sendLoginResponse($request);
                    }
                    
                }else{
                    return redirect()->route('login')->with('error','your email and password are wrong.');
                }
            }
            
            
        }
        else{
            return redirect()->route('login')->with('error','your email and password are wrong.');
        }
		
	}
	
    /**
     * @param Request $request
     * @param $user
     *
     * @throws GeneralException
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function authenticated(Request $request, $user)
    {
        /*
         * Check to see if the users account is confirmed and active
         */
        //to do

        //if(!empty($user->mobile_verified_at)){
            if (!$user->status) {
                auth()->logout();
                return redirect()->route('login')->with('error', 'Currently, your account is not active. Please contact to site administrator.');
           // } elseif (\Auth::user()->hasRole(['student','tutor'])) {
            //    return redirect()->route('frontend.profile')->with('success','You have successfully logged in');
            } elseif (\Auth::user()->hasRole(['superadmin', 'admin'])) {
                // && \Auth::user()->hasPermission('browse.admin')
                return redirect()->route('backend.dashboard');
            }
        //}else{
        //    auth()->logout();
        //    return redirect()->route('login')->with('error', 'Currently, your account is not active. Please Verfiy Your Email.');
        //}
        return redirect('/');
    }
	
	
}
