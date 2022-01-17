<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentFavourites;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\School;
use App\Models\ReportVideo;
use App\Models\Video;
use App\Models\Tutor;
use App\Models\Subject;
use App\Models\Classes;
use App\Models\Avatar;
use App\Models\Countries;
use App\Models\City;
use App\Models\District;
use App\Models\Zone;
use App\Models\State;
use App\Models\College;
use App\Models\StudentVideo;
use App\Models\StudentHistory;
use App\Models\UserSubscription;
use App\User;
use App\Rules\MatchOldPassword;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use GLB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Image;
use Validator;
use Auth;
use App\Models\Rateing;

use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
       
    }

    /**
     * Show Student profile.
     *
     */
    public function profileFirst()
    {

        $states = State::where('status', '=', 1)->orderBy('name')->pluck('name', 'id');
        if (Auth::user()->userRole->role->slug == 'admin') {
            return redirect()->route('backend.dashboard');
        } else if (Auth::user()->userRole->role->slug == 'student') {
			
            $student = User::where("id",Auth::user()->id)->first();
            return view('frontend.student.profile-first', compact('student','states'));

        } else if (Auth::user()->userRole->role->slug == 'tutor') {

            $tutor = User::where("id",Auth::user()->id)->first();
            return view('frontend.tutor.profile-first', compact('tutor','states'));
            return $this->tutor();
        }
    }

     /**
     * Show Student profile.
     *
     */
    public function profile()
    {
        if (\Auth::user()->profile_completed==0) {
            return redirect()->route('frontend.profile.first')->with('success','You have successfully logged in');
       }
       // session()->forget('newCustomer');
       // session()->forget('userId');
       

        if (Auth::user()->userRole->role->slug == 'admin') {
            return redirect()->route('backend.dashboard');
        } else if (Auth::user()->userRole->role->slug == 'student') {
			
            return $this->student();
        } else if (Auth::user()->userRole->role->slug == 'tutor') {
            return $this->tutor();
        }
    }
	
	public function updateProfileTutor(Request $request)
    {
        //Retrieve the tutor and update
		$user_id = Auth::user()->id;
        $tutor = Tutor::where('user_id',$user_id)->first();
        $data = $request->all();
        
        $validator = Validator::make($data, [
            'first_name' => 'required|min:2',
            'state_name' => 'required',
            'zone_name' => 'required',
            'district_name' => 'required',
            'city_name' => 'required',
            'college_name' => 'required',
            'tutor_subject' => 'required',
            'pricipal_name' => 'required',
                ]);
               
        if ($validator->fails()) {
            return Redirect::back()
                            ->withErrors($validator) // send back all errors to the form
                            ->withInput();
        }

         /** Below code for save photo * */
         if ($request->hasFile('photo')) {

            $validator = Validator::make($request->all(), [
                        'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                            ], [
                        'photo.max' => 'The profile photo may not be greater than 2 mb.',
            ]);

            if ($validator->fails()) {
                return Redirect::back()
                            ->withErrors($validator) // send back all errors to the form
                            ->withInput()->with('error','The profile photo may not be greater than 2 mb');
            }

            $destinationPath = public_path('/uploads/tutor/');
            $newName = '';
            $fileName = $request->all()['photo']->getClientOriginalName();
            $file = request()->file('photo');
            $fileNameArr = explode('.', $fileName);
            $fileNameExt = end($fileNameArr);
            $newName = date('His') . rand() . time() . '__' . $fileNameArr[0] . '.' . $fileNameExt;

            $file->move($destinationPath, $newName);

            $imagePath = 'uploads/tutor/' . $newName;
            $tutor->profile_image = $imagePath;
        }

        $employee_id = "EMP-00".$tutor->id;

        $tutor->first_name = $data['first_name'];
        $tutor->last_name = $data['last_name'];
        $tutor->employee_id = $employee_id;
        $tutor->pricipal_name = $data['pricipal_name'];
        $tutor->state_id = $data['state_name'];
        $tutor->zone_id = $data['zone_name'];
        $tutor->district_id = $data['district_name'];
        $tutor->city_id = $data['city_name'];
        $tutor->college_id = $data['college_name'];
        $tutor->tutor_subject = $data['tutor_subject'];
       
        $tutor->save(); 

        $user_more_info = User::find($tutor->user_id);
        $user_more_info->profile_completed =1;
        $user_more_info->save(); 
        session()->put('user',$user_more_info);
        return redirect()->route('frontend.profile')->with('success', 'Tutor Information Updated Successfully');
    }
	
	public function updateProfileStudent(Request $request)
    {
        //Retrieve the tutor and update
		$user_id = Auth::user()->id;
        $student = Student::where('user_id',$user_id)->first();
        $data = $request->all();
        $validator = Validator::make($data, [
            'first_name' => 'required|min:2',
            'student_type' => 'required',
            'father_name' => 'required',
            'branch' => 'required',
            'state_name' => 'required',
            'zone_name' => 'required',
            'district_name' => 'required',
            'city_name' => 'required',
            'college_name' => 'required',
                        ], [
                   // 'password.regex' => "Password must be contains minimum 8 character with at least one lowercase, one uppercase, one digit, one special character",
        ]);

        if ($validator->fails()) {
            return Redirect::back()
                            ->withErrors($validator) // send back all errors to the form
                            ->withInput();
        }

        /** Below code for save photo * */
        if ($request->hasFile('photo')) {

            $validator = Validator::make($request->all(), [
                        'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                            ], [
                        'photo.max' => 'The profile photo may not be greater than 2 mb.',
            ]);

            if ($validator->fails()) {
                return Redirect::back()
                            ->withErrors($validator) // send back all errors to the form
                            ->withInput()->with('error','The profile photo may not be greater than 2 mb');
            }

            $destinationPath = public_path('/uploads/student/');
            $newName = '';
            $fileName = $request->all()['photo']->getClientOriginalName();
            $file = request()->file('photo');
            $fileNameArr = explode('.', $fileName);
            $fileNameExt = end($fileNameArr);
            $newName = date('His') . rand() . time() . '__' . $fileNameArr[0] . '.' . $fileNameExt;

            $file->move($destinationPath, $newName);

            $imagePath = 'uploads/student/' . $newName;
            $student->profile_image = $imagePath;
        }

        $student_id = "ST-00".$student->id;
        $student->first_name = $data['first_name'];
        $student->last_name = $data['last_name'];
        $student->student_id = $student_id;
        $student->student_type = $data['student_type'];
        $student->father_name = $data['father_name'];
        $student->branch = $data['branch'];
        $student->state_id = $data['state_name'];
        $student->zone_id = $data['zone_name'];
        $student->district_id = $data['district_name'];
        $student->city_id = $data['city_name'];
        $student->college_id = $data['college_name'];
       
        $student->save(); //persist the data
        //save user other information
        $user_more_info = User::find($student->user_id);
        $user_more_info->profile_completed =1;
        $user_more_info->save(); 
        session()->put('user',$user_more_info);

        return redirect()->route('frontend.profile')->with('success', 'Student Information Updated Successfully');
    }
	
    public function tutor()
    {

        $tutor = User::find(Auth::user()->id);
        $states = State::where('status', '=', 1)->orderBy('name')->pluck('name', 'id');
        $zones = Zone::where('status', 1)->where('id', $tutor->userData->zone_id)->orderBy('zone_name')->pluck('zone_name','id');
        $districts = District::where('status', 1)->where('id', $tutor->userData->district_id)->orderBy('name')->pluck('name','id');
        $cities = City::where('status', 1)->where('id', $tutor->userData->city_id)->orderBy('city_name')->pluck('city_name','id');
        $colleges = College::where('status', 1)->where('id', $tutor->userData->college_id)->orderBy('name')->pluck('name','id');
        return view('frontend.tutor.profile', compact('tutor','colleges', 'states', 'zones', 'districts', 'cities'));
    }
	
	
	

    public function student()
    {

        $student = User::where("id",Auth::user()->id)->first();
        $states = State::where('status', '=', 1)->orderBy('name')->pluck('name', 'id');
        $zones = Zone::where('status', 1)->where('id', $student->userData->zone_id)->orderBy('zone_name')->pluck('zone_name','id');
        $districts = District::where('status', 1)->where('id', $student->userData->district_id)->orderBy('name')->pluck('name','id');
        $cities = City::where('status', 1)->where('id', $student->userData->city_id)->orderBy('city_name')->pluck('city_name','id');
        $colleges = College::where('status', 1)->where('id', $student->userData->college_id)->orderBy('name')->pluck('name','id');
        return view('frontend.student.profile', compact('student','colleges', 'states', 'zones', 'districts', 'cities'));
    }

    public function changePassword()
    {
        return view('frontend.change_password');
    }

	/**
     * change password
     * @param Request $request
     * @return Validator object
     */
    public function changePasswordSave(Request $request) {
        $userid = Auth::user()->id;
		$data = $request->all();
		$validator = Validator::make($data, [
				'current_password' => ['required', new MatchOldPassword],
				'password' => 'required|confirmed|string|min:6',
				],[
					// 'password.regex' => "Password must be contains minimum 8 character with at least one lowercase, one uppercase, one digit, one special character",
				]);
       if ($validator->fails()) {
				return Redirect::back()
					->withErrors($validator) // send back all errors to the form
					->withInput();
			}
			

		$data = $request->all();
		$user = User::find($userid);
		if(!empty($user) && isset($user->id) && !empty($user->id)) {
			$user->password = bcrypt($data['password']);
			$user->save();

			return redirect()->route('frontend.changePassword')->with('success', 'Password Updated Successfully');
        } else {
			return redirect()->route('frontend.changePassword')->with('error', 'Password Updated Successfully');
        }
    }
	
	
	
    public function uploadProfile(Request $request)
    {
        $sender_id = 0;
        $status = 0;
        $messageType = '';
        $message = '';
        $watchCount = 1;
        $src = '';

        $validator = Validator::make($request->all(), [
                    //'profile_image'=>'required|image|dimensions:max_width=212,max_height=150|max:2048'
                    'profile_image' => 'required|image|max:2048'
                        ], [
                        //"required" => ""
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors()->all();
            $status = 0;
            $messageType = 'error';
            $message = collect($errors)->implode('<br>');
        } else {

            $userid = \Auth::user()->id;
            $user = User::find($userid);
            $role_id = $user->role_id;
            $userRole = $user->userRole->role->slug;

            if ($request->hasFile('profile_image')) {
                $logo = $request->file('profile_image');
                $uploadPath = "uploads/student";

                if ($userRole == 'tutor') {
                    /** Below code for save tutor image * */
                    $uploadPath = "uploads/tutor";
                } else {
                    /** Below code for save student image * */
                    $uploadPath = "uploads/student";
                }

                $logoName = time() . mt_rand(10, 100) . '.' . $logo->getClientOriginalExtension();
                $location = public_path($uploadPath);
                if (!file_exists($location)) {
                    mkdir($location);
                }
                $isMoved = $logo->move($location . '/', $logoName);
                $img = Image::make($location . '/' . $logoName);

                if ($role_id == 5) {
                    $update = Student::where('user_id', $user->id)->first();
                    $update->profile_image = $uploadPath . '/' . $logoName;
                    $update->save();
                }
                if ($userRole == 'tutor') {
                    /** Below code for save tutor image * */
                    $update = Tutor::where('user_id', $user->id)->first();
                    $update->profile_image = $uploadPath . '/' . $logoName;
                    $update->save();
                } else {
                    /** Below code for save student image * */
                    $update = Student::where('user_id', $user->id)->first();
                    $update->profile_image = $uploadPath . '/' . $logoName;
                    $update->save();
                }

                $src = url($uploadPath . '/' . $logoName);
                $status = 1;
                $messageType = 'success';
                $message = "Successfully updated your profile.";
            }
        }

        $returnMsg = (object) array(
                    'status' => 200,
                    'errStatus' => $status,
                    'messageType' => $messageType,
                    'message' => $message,
                    'imgsrc' => $src
        );
        $returnData['data'] = $returnMsg;
        return response()->json($returnMsg, 200);
    }

    public function changeAvatar(Request $request)
    {
        $sender_id = 0;
        $status = 0;
        $messageType = 'error';
        $message = 'Please select avatar.';
        $watchCount = 1;
        $src     = '';
        $fileurl = '';
		
		$avatar = Avatar::find($request->avatar_id);
		
		if(!empty($avatar->file_url) && file_exists(public_path($avatar->file_url))){
			$fileurl = $avatar->file_url; 
		}

        if (!empty($request->avatar_id)) {
            $userid = \Auth::user()->id;
            $user = User::find($userid);
            $role_id = $user->role_id;
            $userRole = $user->userRole->role->slug;


            if ($userRole == 'tutor') {
                /** Below code for save tutor  * */
                $update = Tutor::where('user_id', $user->id)->first();
                $update->avatar_id     = $request->avatar_id;
				$update->profile_image = $fileurl;
                $update->save();
            } else {
                /** Below code for save student  * */
                $update = Student::where('user_id', $user->id)->first();
                $update->avatar_id     = $request->avatar_id;
				$update->profile_image = $fileurl;
                $update->save();
            }
			
			$src = $user->userData->profile_or_avatar_image;
			//profile_or_avatar_image
            $status = 1;
            $messageType = 'success';
            $message = "Successfully updated your avatar.";
        }
		
		
		 


        $returnMsg = (object) array(
                    'status' => 200,
                    'errStatus' => $status,
                    'messageType' => $messageType,
                    'message' => $message,
					'imgsrc' => $src
        );
        $returnData['data'] = $returnMsg;
        return response()->json($returnMsg, 200);
    }

    

    /**
     * Uplaod video files
     *
     * @param  string  $uuid
     * @return renderd view
     */
    public function uploadVideoFile($uuid)
    {        
        $video = Video::uuid($uuid);	
        if($video->video_type != 'file' || Auth::user()->id != $video->tutor_id){
            return redirect()->route('front');
        }                
        
        $video_thumb = $video->getVimeoThumb();
        
        return view('frontend.tutor.upload_video_file', compact('video','video_thumb'));
    }
	
	
	public function mylearningList(Request $request)
    {
		$user = Auth::user();
		$data = UserSubscription::with('course','subject','user')->where("user_id",$user->id)->paginate(20);
		
		return view('frontend.my-learning',compact('data'));
	}
	
	public function mylearningStart(Request $request,$id,$subjectId,$videoUid=null,$tab=0)
    {
		$user = Auth::user();
		$data = UserSubscription::where("id",$id)->where("user_id",$user->id)->where("subject_id",$subjectId)->first();
		if(!$data)
		{
			return redirect()->route('mylearningList')->with('error', 'Course not available currently');
		}
		
		$subject = Subject::with('topics','topics.videos','subject_class')->where('id', '=', $subjectId)->where('status', '=', 1)->orderBy('created_at','DESC')->first();
		
		$course = Course::where('status', '=', 1)->where('id', '=', $subject->course_id)->first();
		if($videoUid==null)
		{
			$video = Video::where('status', '=', 1)->where('subject_id', '=', $subject->id)->where('video_upload_type', '=', 'main')->first();
		}
		else{
		$video = Video::where('uuid', '=', $videoUid)->where('status', '=', 1)->where('subject_id', '=', $subject->id)->first();	
		}

        $averageRating = Rateing::where('video_id', $video->id)->avg('rating');
        $myRateing = 0;
        $myRateingGiven  = Rateing::where('video_id', $video->id)->where('user_id', $user->id)->sum('rating');
        if(isset($myRateingGiven))
        $myRateing = $myRateingGiven;

       
        $isFav = 0;
		$fav = StudentFavourites::where(['video_id' => $video->id, 'student_id' => $user->id])->first();
        if(isset($fav))
        $isFav = 1;
		
        $this->studentVideo($video->id, $user->id);
        $video_watch_count = StudentVideo::where('video_id', $video->id)->sum('video_watch_count');

		return view('frontend.my-learning-details',compact('subject','course','video','data','tab','isFav','video_watch_count','averageRating','myRateing'));
	}
	
    public function setRatingvideo(Request $request)
    {
        $data = $request->all();
        $user = Auth::user();
        $count  = Rateing::where('video_id', $data['postid'])->where('user_id', $user->id)->count();
       
        if($count == 0){
            $insert = new Rateing;
        }
        else
        {
            $insert  = Rateing::where('video_id', $data['postid'])->where('user_id', $user->id)->first();
        }

        $insert->user_id = $user->id;
        $insert->video_id = $data['postid'];
        $insert->rating = $data['rating'];
        $insert->save();
        $averageRating = Rateing::where('video_id', $data['postid'])->avg('rating');
        $return_arr = array("averageRating"=>$averageRating,"message"=>"Thank you for giving us a rating");

        return json_encode($return_arr);
    }
    
	public function setFavourites(Request $request)
    {


        $student_id = 0;
        $isLogin = 0;
        

        if (Auth::check()) {
            $student_id = Auth::user()->id;
            $isLogin = 1;
        }


        if ($student_id > 0) {

            $check = StudentFavourites::where(['video_id' => $request->input('video_id'), 'student_id' => $student_id])->first();

            if (!empty($check->id)) {

                $check->delete();
                return Redirect::back()->with('success', 'Video Successfully removed in your favourite list.');
            } else {
                $insert = new StudentFavourites;
                $insert->student_id = $student_id;
                $insert->video_id = $request->get('video_id');
                $insert->save();
                return Redirect::back()->with('success', 'Video Successfully added in your favourite list.');
            }
        } else {

            return Redirect::back()->with('error', 'You are not loged in.');
        }


       
    }


    function studentVideo($video_id,$student_id){
        
	
    if ($student_id > 0) {
            $StudentVideo = StudentVideo::where([
                        'video_id' => $video_id,
                        'student_id' => $student_id
                    ])
                    ->first();

            if (!empty($StudentVideo->id)) {
                $insertUpdate = $StudentVideo;
                $watchCount = $StudentVideo->video_watch_count + 1;
            } else {
                $insertUpdate = new StudentVideo;
                $watchCount = 1;
            }
            
            $insertUpdate->video_id = $video_id;
            $insertUpdate->student_id = $student_id;
            $insertUpdate->video_watch_count = $watchCount;
            $insertUpdate->save();

            $studentHistory = new StudentHistory;
            $studentHistory->student_id = $student_id;
            $studentHistory->video_id = $video_id;
            $studentHistory->save();

            $status = 1;
            $messageType = 'Success';
            $message = 'Successfully played video.';
        }
    }


    public function studentFavourites(Request $request)
    {
        $student_id = 0;
        $page = $request->input('page', 1);
        if (\Auth::check()) {
            $student_id = \Auth::user()->id;
        }

        $tab = $request->tab;
        $loadMore = $request->loadMore;


        $studentFavourites = StudentFavourites::where([
                    'student_id' => $student_id
                ])
                ->whereHas('video', function($q) use($request) {
                    if (!empty($request->course_id)) {
                        $q->where('course_id', $request->course_id);
                    }
                    if (!empty($request->school_id)) {
                        $q->where('school_id', $request->school_id);
                    }
                })
                ->whereNotNull('video_id')
                ->orderBy('id', 'desc')
                ->paginate(GLB::paginate());

        return view('frontend.student.student_favourites_list', compact('tab', 'loadMore', 'studentFavourites', 'page'));
    }

    public function flegVideo(Request $request)
    {
        $user_id = 0;
        $isLogin = 0;

        if (Auth::check()) {
            $user_id = Auth::user()->id;
            $isLogin = 1;
        }

        $validator = Validator::make($request->all(), [
                    'message' => 'required'
                        ], [
                    "message.required" => "Please describe your reason."
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors()->all();
            $status = 0;
            $messageType = 'error';
            $message = collect($errors)->implode('<br>');
        } else {

            
            if ($user_id > 0) {

                $check = ReportVideo::where(['video_id' => $request->input('video_id'), 'user_id' => $user_id])->first();

                if (!empty($check->id)) {

                    $check->delete();
                    $status = 1;
                    $messageType = 'success';
                    $message = 'Successfully removed your reported video.';
                    $fleg_status = 0;
                } else {

                    $insert = new ReportVideo;
                    $insert->user_id  = $user_id;
                    $insert->video_id = $request->input('video_id');
                    $insert->message  = $request->input('message');
                    $insert->save();
                    $fleg_status = 1;

                    $status = 1;
                    $messageType = 'success';
                    $message = 'Successfully reported your video.';
                }
            } else {

                $status = 0;
                $isLogin = 0;
                $messageType = 'error';
                $message = 'You are not loged in.';
            }

        }

        $returnMsg = (object) array(
                    'status' => 200,
                    'errStatus' => $status,
                    'isLogin' => $isLogin,
                    'messageType' => $messageType,
                    'fleg_status' => $fleg_status,
                    'video_id' => $request->input('video_id'),
                    'message' => $message
        );
        $returnData['data'] = $returnMsg;
        return response()->json($returnMsg, 200);
    }
    
}
