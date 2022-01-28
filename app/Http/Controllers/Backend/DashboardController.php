<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\SiteHelpers;
use Auth;
use App\Models\School;

class DashboardController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
		$school_id = '';
       
        
		$school_count = SiteHelpers::dashboard_resource_count('schools');
		$courses_count = SiteHelpers::dashboard_resource_count('courses');
		$subject_count = SiteHelpers::dashboard_resource_count('subjects');
		$topic_count = SiteHelpers::dashboard_resource_count('topics');
		$videos_count = SiteHelpers::dashboard_resource_count('videos');
		$student_count = SiteHelpers::dashboard_resource_count('students');
		$tutor_count = SiteHelpers::dashboard_resource_count('tutors');
		
		$title = "BhiLearning Dashboard";
		
			
			return view('backend.dashboard', compact('title', 'school_count', 'courses_count', 'subject_count', 'topic_count', 'videos_count', 
												 'student_count', 'tutor_count'));
		
	}
}