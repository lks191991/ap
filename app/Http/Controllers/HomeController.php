<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Classes;
use App\Models\Video;
use App\Models\Rateing;
use DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth', ['except' => array('index','courseList','contactUs','contactUsPost')]);
    }
	
	/**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
   /*  public function indexWithClass()
    {
		//session()->forget('newCustomer');
		$topCourses = Subject::where('status', '=', 1)->limit(4)->get();
		$allCoursesList = Classes::where('status', '=', 1)->get();
		$latestCourses = Subject::where('status', '=', 1)->limit(8)->orderBy('created_at','DESC')->get();
		
        return view('frontend.homeWithClass',compact('topCourses','latestCourses','allCoursesList'));
    } */
	
	public function index()
    {
		//session()->forget('newCustomer');
		$topCourses = Course::where('status', '=', 1)->limit(4)->get();
		$allCoursesList = Course::where('status', '=', 1)->get();
		$latestCourses = Course::where('status', '=', 1)->limit(8)->orderBy('created_at','DESC')->get();
		
        return view('frontend.home',compact('topCourses','latestCourses','allCoursesList'));
    }
	
	
	public function courseList(Request $request,$CourseTypeId)
    {
		
		$allCourses = Course::with('videos')->where('school_id', '=', $CourseTypeId)->where('status', '=', 1)->orderBy('created_at','DESC')->paginate(20);
		
        return view('frontend.list',compact('allCourses'));
    }
	

	public function courseSearch(Request $request)
    {
		$data = $request->all();
		
		$query = Course::where('status', '=', 1);
		
		if(isset($data['search_text']) and !empty($data['search_text']))
		{
			$query->where('name', 'like', '%' . $data['search_text'] . '%');
		}
		$allCourses = $query->orderBy('created_at','DESC')->paginate(20);
		
        return view('frontend.course_search',compact('allCourses'));
    }
	
	public function courseDetails($uuid)
    {
		
		
		
        $course = Course::where('status', '=', 1)->where('uuid', '=', $uuid)->first();
		
		$subjects = Subject::with('topics')->where('course_id', '=', $course->id)->where('status', '=', 1)->orderBy('created_at','DESC')->has('videos')->get();
		if($course->demo_video_url=='')
		{
		return redirect()->route('course-list',[$course->id])->with('error', 'Course not available currently');

		}

        return view('frontend.subject_details',compact('course','subjects'));
    }
	
	
	
	public function autoSearch(Request $request)
    {
          $query = $request->get('query');
          $filterResult = Subject::where('subject_name', 'LIKE', '%'. $query. '%')->get();
          return response()->json($filterResult);
    } 
	
	
}
