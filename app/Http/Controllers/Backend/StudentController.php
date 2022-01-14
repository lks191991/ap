<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\School;
use App\Models\SchoolCategory;
use App\Models\Course;
use App\Models\Classes;
use App\Models\StudentClasses;
use App\Models\City;
use App\Models\District;
use App\Models\Zone;
use App\Models\State;
use App\Models\College;
use App\User;
use Illuminate\Validation\Rule;
use Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use App\Mail\sendEmailtoSchoolstudent;
use Illuminate\Support\Facades\Mail;
use Auth;
use GLB;
use DB;
use App\Models\StudentHistory;
use App\Models\Video;
use App\Models\StudentVideo;
use App\Models\Question;
use App\Models\StudentDownload;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$courses = array();
		$classes = array();
		
        $query = Student::where('id','<>',0);
        $students = $query->orderBy('id', 'desc')->get();
        
        return view('backend.students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$states = State::where('status', '=', 1)->orderBy('name')->pluck('name', 'id');

        return view('backend.students.create', compact('states'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
                    'first_name' => 'required|min:2',
                    'email' => 'email|max:255|unique:users',
                    'student_type' => 'required',
                    'father_name' => 'required',
                    'mobile' => 'required|numeric',
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

        //Persist the school manager in the database
        //form data is available in the request object
        $student = new Student();

        /** Below code for save photo * */
        if ($request->hasFile('photo')) {

            $validator = Validator::make($request->all(), [
                        'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                            ], [
                        'photo.max' => 'The profile photo may not be greater than 2 mb.',
            ]);

            if ($validator->fails()) {
                return redirect()->route('backend.students.create')->withErrors($validator)->withInput();
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
        DB::beginTransaction();
        try {
        $user = User::create([
                    'username' => $data['email'],
                    'email' => $data['email'],
                    'mobile' => $data['mobile'],
                    'password' => Hash::make('123456')
        ]);

        $role = \DB::table('roles')->where('slug', '=', 'student')->first();
        $user->attachRole($role, $user->id);

        $student->user_id = $user->id;
		$student->username = $data['email'];
        $student->first_name = $data['first_name'];
        $student->last_name = $data['last_name'];
        $student->email = $data['email'];
        $student->mobile = $data['mobile'];
        $student->student_id = $data['student_id'];
        $student->student_type = $data['student_type'];
        $student->father_name = $data['father_name'];
        $student->branch = $data['branch'];
        $student->state_id = $data['state_name'];
        $student->zone_id = $data['zone_name'];
        $student->district_id = $data['district_name'];
        $student->city_id = $data['city_name'];
        $student->college_id = $data['college_name'];
        $student->status = ($data['status'] !== null) ? $data['status'] : 0;
        

        $student->save(); //persist the data 
        $student_id = "ST-00".$student->id;
        $studentdata = Student::findOrFail($student->id);
        $studentdata->student_id =$student_id;
        $studentdata->save();

        //save user other information
        $user_more_info = User::find($user->id);
        $user_more_info->name = $data['first_name'];
        $user_more_info->mobile_verified_at = date('Y-m-d H:i:s');
        $user_more_info->save(); //persist the data
        DB::commit();
        } catch (\Exception $e) {
			DB::rollback();
			return redirect()->back()->withInput()->with('error', 'something went wrong please try again');
		}
        if (!empty($user->email))
           // Mail::to($user->email, "New student on " . env('APP_NAME', 'Bright-Horizon'))->send(new sendEmailtoSchoolstudent($studentdata));


        return redirect()->route('backend.students.index')->with('success', 'Student Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        
		$student_videos = StudentVideo::where('student_id', $student->user_id)->pluck('video_id', 'video_id');
		//pr($student_videos); exit;
        $classesWatched = Video::whereIn('id', $student_videos)->count();
        $questionsCount = Question::where('sender_id', $student->user_id)->where('type', 'question')->count();
        $replyCount = Question::where('sender_id', $student->user_id)->where('type', 'reply')->count();
        $noteDownloads = StudentDownload::where([
                    'student_id' => $student->user_id,
                    'status' => 1
                ])->count();
		
		$page = $request->input('page', 1);
		
		$studentHistories = StudentHistory::where([
                    'student_id' => $student->user_id
                ])
               ->whereHas('video', function($q) use($request) {
                 /*    if (!empty($request->course_id)) {
                        $q->where('course_id', $request->course_id);
                    }
                    if (!empty($request->school_id)) {
                        $q->where('school_id', $request->school_id);
                    }
					*/
                }) 
                ->whereNotNull('video_id')
                ->orderBy('id', 'desc')
                ->paginate(GLB::paginate());
				
			//	pr($studentHistories); exit;
			
		return view('backend.students.show', compact('student', 'studentHistories', 'classesWatched', 'questionsCount', 'replyCount', 'noteDownloads'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $school_id = '')
    {
		//Find the student
        $student = Student::find($id);
       
        $states = State::where('status', '=', 1)->orderBy('name')->pluck('name', 'id');
        $zones = Zone::where('status', 1)->where('id', $student->zone_id)->orderBy('zone_name')->pluck('zone_name','id');
        $districts = District::where('status', 1)->where('id', $student->district_id)->orderBy('name')->pluck('name','id');
        $cities = City::where('status', 1)->where('id', $student->city_id)->orderBy('city_name')->pluck('city_name','id');
        $colleges = College::where('status', 1)->where('id', $student->college_id)->orderBy('name')->pluck('name','id');
        
		
        return view('backend.students.edit', compact('student','colleges', 'states', 'zones', 'districts', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Retrieve the student and update
        $student = Student::find($id);
        $data = $request->all();
        $user_id = $student->user_id;
        $validator = Validator::make($data, [
                    'first_name' => 'required|min:2',
                    'password' => 'nullable|confirmed|string|min:6',
                    'email' => [
                        'email',
                        'required',
                        'max:180',
                        Rule::unique('users')->where(function ($query) use($user_id) {
                                    return $query->where('id', '<>', $user_id);
                                })
                    ],
                    'student_type' => 'required',
                    'father_name' => 'required',
                    'mobile' => [
                        'required',
                        'numeric',
                        Rule::unique('students')->where(function ($query) use($user_id) {
                                    return $query->where('user_id', '<>', $user_id);
                                })
                    ],
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

        if ($request->hasFile('photo')) {

            $validator = Validator::make($request->all(), [
                        'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                            ], [
                        'photo.max' => 'The profile photo may not be greater than 2 mb.',
            ]);

            if ($validator->fails()) {
                return redirect()->route('backend.students.edit', $id)->withErrors($validator)->withInput();
            }

            $destinationPath = public_path('/uploads/student/');
            $newName = '';
            $fileName = $request->all()['photo']->getClientOriginalName();
            $file = request()->file('photo');
            $fileNameArr = explode('.', $fileName);
            $fileNameExt = end($fileNameArr);
            $newName = date('His') . rand() . time() . '__' . $fileNameArr[0] . '.' . $fileNameExt;

            $file->move($destinationPath, $newName);

            $oldImage = public_path($student->profile_image);
            //echo $oldImage; exit;
            if (!empty($student->profile_image) && file_exists($oldImage)) {
                unlink($oldImage);
            }

            $imagePath = 'uploads/student/' . $newName;
            $student->profile_image = $imagePath;
        }

        $user_more_info = User::find($student->user_id);
        if ($data['email'] != $student->email) {
            $user_more_info->email = $data['email'];
        }
        if ($data['mobile'] != $student->mobile) {
            $user_more_info->mobile = $data['mobile'];
        }

        $user_more_info->username = $data['email'];
        $user_more_info->save(); 

		$student->username = $data['email'];
        $student->first_name = $data['first_name'];
        $student->last_name = $data['last_name'];
        $student->email = $data['email'];
        $student->mobile = $data['mobile'];
        $student->student_type = $data['student_type'];
        $student->father_name = $data['father_name'];
        $student->branch = $data['branch'];
        $student->state_id = $data['state_name'];
        $student->zone_id = $data['zone_name'];
        $student->district_id = $data['district_name'];
        $student->city_id = $data['city_name'];
        $student->college_id = $data['college_name'];
        $student->status = ($data['status'] !== null) ? $data['status'] : 0;
        $student->save(); //persist the data
       
        return redirect()->route('backend.students.index')->with('success', 'Student Information Updated Successfully');
    }
	
	/**
     * form to assigned classes to student.
     *
     * @param  int  $uuid
     * @return \Illuminate\Http\Response
     */
	public function assignedclasses($uuid) {
	
		$student = Student::where('uuid',$uuid)->first();
        
		if(isset($student->school_id) && !empty($student->school_id)) {
			$courses = Course::orderBy('name')->where('status','=',1)->where('school_id','=',$student->school_id)->get();
		} else {
			$courses = array();
		}
		
		//pr($courses); exit;
		
		$studentclasses = StudentClasses::where('user_id', $student->user_id)->select('class_id')->pluck('class_id')->toArray();
		//pr($studentclasses); exit;
        return view('backend.students.assignedclasses', compact('student', 'courses', 'studentclasses'));
	
	}
	
	/**
     * form to assigned classes to student.
     *
     * @param  int  $uuid
     * @return \Illuminate\Http\Response
     */
	public function save_assignedclasses(Request $request) {
	
		$count_assigned_classes = StudentClasses::where('user_id', $request->input('user'))->count();
		
		//Check for the user profile      
        if(Auth::user()->hasRole('school')){
            $profile = Auth::user()->profile;
            if(isset($profile->school_id)){
                $school_id = $profile->school_id;
                $school = new School();
				$student_add_permission = $school->schoolHasLimit($profile->school_id);
				if(!$student_add_permission && $count_assigned_classes < 1) {
					return Redirect::back()->with('warning', 'Class Assign Limit Exceed');
				}
				
            }            
        }
		
		$data = $request->all();
		
		if(!Auth::user()->hasAccessToSchool($request->input('school'))){
            return redirect()->route('backend.dashboard');           
        }
		
		$count_assigned_classes = StudentClasses::where('user_id', $request->input('user'))->count();
		
		//pr($data); exit;
		if(!isset($data['class_id']) && empty($data['class_id']) && $count_assigned_classes < 1) {
			
			return Redirect::back()
                            ->withErrors(['error'=>'Please select at least one class']) // send back all errors to the form
                            ->withInput();
		} 
		
		$student = Student::where('user_id',$request->input('user'))->first();
		
		StudentClasses::where('user_id', $request->input('user'))->delete();
		
		if(!empty($data['class_id'])) {
			foreach($data['class_id'] as $key=>$val) {
				$student_class = new StudentClasses();
				$student_class->school_id = $request->input('school');
				$student_class->user_id = $request->input('user');
				$student_class->class_id = $val;
				$student_class->save();
			}
		}
		return redirect()->route('backend.students.assignedclasses', $student->uuid)->with('success', 'Classes Information Successfully Saved.');
	
	}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $student = Student::find($id);
		
		//delete user
        $user = User::find($student->user_id);
        $user->delete();

		//delete assign classes if any
		StudentClasses::where('user_id', $student->user_id)->delete();
		
        $student->delete();


        return redirect()->route('backend.students.index')->with('success', 'Student Deleted Successfully');
    }

}
