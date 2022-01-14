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
use App\Models\City;
use App\Models\District;
use App\Models\Zone;
use App\Models\State;
use App\Models\College;
use \App\Models\SchoolCategory;
use App\Models\Course;
use App\Models\School;
use App\Models\Tutor;
use App\Models\Classes;
use Illuminate\Auth\Events\Registered;
use App\Mail\sendEmailtoNewuser;
use Illuminate\Support\Facades\Mail;
use Auth;


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
		if($data['rejister_as'] == 'student') {
			return Validator::make($data, [
                        'rejister_as' => 'required',
						'first_name' => 'required|regex:/^[a-zA-Z_\-]*$/',
						'last_name' => 'nullable|regex:/^[a-zA-Z_\-]*$/',
                        'email' => 'email|max:255|unique:users',
                        'student_id' => 'required',
                        'student_type' => 'required',
                        'mobile' => 'required|numeric|unique:students',
                        'father_name' => 'required',
						'password' => 'required|confirmed|string|min:6',
						'branch' => 'required',
						'state_name' => 'required',
                        'zone_name' => 'required',
                        'district_name' => 'required',
                        'city_name' => 'required',
                        'college_name' => 'required',
                        'photo' => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
							], [
						'first_name.regex' => "First Name contains <li>The first name must contain alpha characters only</li>",
						'last_name.regex' => "Last Name contains <li>The last name must contain alpha characters only</li>",
						'rejister_as.required' => "Register as required.",
                        'photo.max' => 'The profile photo may not be greater than 2 mb.',
							// 'password.regex' => "Password contains <ul><li>At least one lowercase</li><li>At least one uppercase</li><li>At least one digit</li><li>At least one special character</li><li>At least it should have 8 characters long</li></ul>",
			]);
		} else  {
		
			return Validator::make($data, [
						'first_name' => 'required|regex:/^[a-zA-Z_\-]*$/',
						'last_name' => 'nullable|regex:/^[a-zA-Z_\-]*$/',
						'password' => 'required|confirmed|string|min:6',
						'email' => 'email|max:255|unique:users',
						'mobile' => 'required|numeric|unique:tutors',
						'tutor_subject' => 'required',
						'rejister_as' => 'required'
							], [
						'first_name.regex' => "First Name contains <li>The first name must contain alpha characters only</li>",
						'last_name.regex' => "Last Name contains <li>The last name must contain alpha characters only</li>",
						'rejister_as.required' => "Register as required.",
						'tutor_subject.required' => "Please Enter your subject",
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
			$countries = Countries::where(['status' => 1])->select('id', 'name')->orderBy('name', 'ASC')->get();
            return view('auth.register',compact('countries'));
    }

    public function showRegistrationFormTutor($num = null)
    {

 $states = State::where('status', '=', 1)->orderBy('name')->pluck('name', 'id');
        $zones = Zone::where('status', 1)->orderBy('zone_name')->pluck('zone_name','id');
        $districts = District::where('status', 1)->orderBy('name')->pluck('name','id');
        $cities = City::where('status', 1)->orderBy('city_name')->pluck('city_name','id');
        $colleges = College::where('status', 1)->orderBy('name')->pluck('name','id');            
        return view('auth.register_tutor',compact('colleges', 'states', 'zones', 'districts', 'cities'));
    }

    public function showRegistrationFormStudent($num = null)
    {
        $states = State::where('status', '=', 1)->orderBy('name')->pluck('name', 'id');
        $zones = Zone::where('status', 1)->orderBy('zone_name')->pluck('zone_name','id');
        $districts = District::where('status', 1)->orderBy('name')->pluck('name','id');
        $cities = City::where('status', 1)->orderBy('city_name')->pluck('city_name','id');
        $colleges = College::where('status', 1)->orderBy('name')->pluck('name','id');
            return view('auth.register_student',compact('colleges', 'states', 'zones', 'districts', 'cities'));
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
      
        

        if ($rejister_as == 'tutor') {
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
           //Mail::to($user->email, "New registration on " . env('APP_NAME', ''))->send(new sendEmailtoNewuser($user, $rejister_as));

        return $user;
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
		$data = $request->all();
       //$user = $this->create($request->all());

        $register_as = $data['rejister_as'];
        if ($register_as == 'student') {
            $user = User::create([
                'username' => $data['email'],
                'email' => $data['email'],
                'password' => Hash::make($data['password'])
    ]);

    //$user = User::find(2);

    $role = \DB::table('roles')->where('slug', '=', $data['rejister_as'])->first();  //choose the default role upon user creation.

    /* Below code for assign user role */
    $user->attachRole($role, $user->id);

    $rejister_as = $data['rejister_as'];
    

    if ($rejister_as == 'student') {
        /* Below code for save student data */
        /** Below code for save photo * */
        $data['imagephoto'] = '';
        if ($request->hasFile('photo')) {

            $destinationPath = public_path('/uploads/student/');
            $newName = '';
            $fileName = $request->all()['photo']->getClientOriginalName();
            $file = request()->file('photo');
            $fileNameArr = explode('.', $fileName);
            $fileNameExt = end($fileNameArr);
            $newName = date('His') . rand() . time() . '__' . $fileNameArr[0] . '.' . $fileNameExt;
    
            $file->move($destinationPath, $newName);
    
            $imagePath = 'uploads/student/' . $newName;
            $data['imagephoto'] = $imagePath;
        }
        $user->insertStudent($user, $data);
    }
            return redirect("/student-register")->with('success', 'You are successfully registered please login.');;
        }

        if ($register_as == 'tutor') {
            return redirect("/tutor-register")->with('success', 'You are successfully registered. Your detail is sent to admin once approved you can log in.');;
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
        $verifyUser = DB::table('users')->where('token', $token)->first();

        if (isset($verifyUser)) {
            if (empty($verifyUser->email_verified_at)) {
                DB::table('users')->where('token', $token)->update(['email_verified_at' => Carbon::now()]);
                $status = "Your account is verified. You can now login.";
            } else {
                $status = "Your account is already verified. You can now login.";
            }
            return redirect('/login')->with('success', $status);
        } else {
            return redirect('/login')->with('error', "Sorry your verification token not valid.");
        }
    }

}
