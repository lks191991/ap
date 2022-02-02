<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Course;
use App\Models\SchoolCategory;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Validation\Rule;
use Validator;
use Illuminate\Support\Facades\Redirect;
use Auth;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {	

			$courses = Course::orderBy('id', 'desc')->get();
		
		//pr($schools); 
		//echo json_encode($schools);
		//exit;
        return view('backend.course.index',compact('courses'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($school_id = '')
    {
		if(Auth::user()->hasRole('school')){
            return redirect()->route('backend.dashboard');          
        }
	
       
		
			
		return view('backend.course.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		 //Persist the course in the database
        //form data is available in the request object
        $course = new Course();
		
		$institute_type = $request->input('institute_type');
		$ajax_request = $request->input('ajax_request');
		
			
			$validator = Validator::make($request->all(), [
				'name' => 'required|unique:courses,name|max:180',   
				
			]);
		
		if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator) // send back all errors to the form
                ->withInput();
        }
		
		
       //input method is used to get the value of input with its
        //name specified
		$course->name = $request->input('name');
		$course->school_id = 1;
		$course->description = '';
		$course->status = ($request->input('status') !== null)? $request->input('status'):0;
		$course->save(); //persist the data
		
			return redirect()->route('backend.courses')->with('success','Institution Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		$course = Course::findOrFail($id);
		
		if(!Auth::user()->hasAccessToSchool($course->school_id)){
            return redirect()->route('backend.dashboard');           
        }
		
		return view('backend.course.show', compact('course'));
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		if(Auth::user()->hasRole('school')){
            return redirect()->route('backend.dashboard');          
        }
		
       //Find the course
        $course = Course::find($id);
		
		
		
		return view('backend.course.edit',compact('course'));

    }
	
	/**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit_ajax(Request $request)
    {
		$id = $request->input('course_id');
		$course = Course::find($id);
		
		$department_ajax_request = $request->input('department_ajax_request');
		
		return view('backend.course.edit-ajax', compact('course', 'department_ajax_request'));
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
        //Retrieve the employee and update
        $course = Course::find($id);
		//echo "<pre>"; print_r($school); exit;
      //  $course->school_id = $request->input('school_name');
		$school_id = $course->school_id;
		
		$institute_type = $request->input('institute_type');
		
		
			
			$validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'max:180',
                    Rule::unique('courses')->where(function ($query) use($id) {  
                        return $query->where('id','<>', $id);
                    })
                ],
				
			]);
		
		if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator) // send back all errors to the form
                ->withInput();
        }
		
		
		$course->name = $request->input('name');
		$course->status = ($request->input('status') !== null)? $request->input('status'):0;
        $course->save(); //persist the data
		
        return redirect()->route('backend.courses')->with('success','Institution Information Updated Successfully');
		
        

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
		$course = Course::find($id);
		
		if(isset($course->id) && !empty($course->id)){
				$classes = Classes::where('course_id', $course->id)->select('id')->get();
				foreach($classes as $class) {
					if(isset($class->id) && !empty($class->id)) {
						$subjects = Subject::where('class_id', $class->id)->select('id')->get();
						foreach($subjects as $subject) {
							if(isset($subject->id) && !empty($subject->id)) {
								$topics = Topic::where('subject_id', $subject->id)->select('id')->get();
								foreach($topics as $topic) {
									if(isset($topic->id) && !empty($topic->id))
									$topic->delete();
								}
								
								$subject->delete();
							}
						}
						
			
						$class->delete();
					}
				}
			}
		
        $course->delete();
		
			return redirect()->route('backend.courses')->with('success','Institution Deleted Successfully');
	}
}
