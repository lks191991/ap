<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentFavourites;
use App\Models\StudentVideo;
use App\Models\State;
use App\Models\City;
use App\Models\District;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\Zone;
use App\Models\College;
use Illuminate\Validation\Rule;
use Validator;
use Illuminate\Support\Facades\Redirect;
use Auth;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
      public function favouritedVideosList(Request $request)
    {

		$filter = $request->all();
		
	
        
        $query = $studentFavourites = StudentFavourites::with('user')->where('id','<>',0);
        
		if(isset($filter))
		{
			$query->whereHas('user', function($q) use($filter) {
                    if (!empty($filter['emailinput'])) {
                        $q->where('email', $filter['emailinput']);
                    }
					if (!empty($filter['contactinput'])) {
                        $q->where('mobile', $filter['contactinput']);
                    }
					if (!empty($filter['your_name'])) {
						$q->where('first_name','LIKE',"%".$filter['your_name']."%");
                    }
				 });
		}
		
		
                $query->whereNotNull('video_id');
                $query->orderBy('id', 'desc');
                $fvideos = $query->paginate(20);
		
		
		
		return view('backend.reports.favourite-videos-list', compact('fvideos'));
    }

 
    public function studentVideoswatch(Request $request)
    {

		$filter = $request->all();
		
	
        
        $query = $studentFavourites = StudentVideo::with('user')->where('id','<>',0);
       
		if(isset($filter))
		{
			$query->whereHas('user', function($q) use($filter) {
                    if (!empty($filter['emailinput'])) {
                        $q->where('email', $filter['emailinput']);
                    }
					if (!empty($filter['contactinput'])) {
                        $q->where('mobile', $filter['contactinput']);
                    }
					if (!empty($filter['your_name'])) {
						$q->where('first_name','LIKE',"%".$filter['your_name']."%");
                    }
				 });
		}
		
                $query->whereNotNull('video_id');
                $query->orderBy('id', 'desc');
                $fvideos = $query->paginate(20);
		
		
		return view('backend.reports.student-videos-watch', compact('fvideos'));
    }
    
	public function totalVideoswatch(Request $request)
    {

		$filter = $request->all();
	
        $query = $studentFavourites = StudentVideo::where('id','<>',0);
		
                $query->whereNotNull('video_id');
				$query->selectRaw('*, sum(video_watch_count) as sumTotal');

				$query->groupBy('video_id');
                $query->orderBy('id', 'desc');
                $fvideos = $query->paginate(20);
		
		
		return view('backend.reports.total-videos-watch', compact('fvideos'));
    }

    public function totalCountStudentTutor(Request $request)
    {

		$filter = $request->all();
		$states = State::where('status', '=', 1)->orderBy('name')->pluck('name', 'id');
        $zones = Zone::where('status', '=', 1)->orderBy('zone_name')->pluck('zone_name', 'id');
        $districts = District::where('status', '=', 1)->orderBy('name')->pluck('name', 'id');
        $cities = City::where('status', '=', 1)->orderBy('city_name')->pluck('city_name', 'id');
        $colleges = College::where('status', '=', 1)->orderBy('name')->pluck('name', 'id');
        
        $query =  Student::where('id','<>',0);
        $query2 =  Tutor::where('id','<>',0);
        
		if(isset($filter))
		{
                    if (!empty($filter['state'])) {
                        $query->where('state_id', $filter['state']);
                        $query2->where('state_id', $filter['state']);
                    }
					if (!empty($filter['zone'])) {
                        $query->where('zone_id', $filter['zone']);
                        $query2->where('zone_id', $filter['zone']);
                    }
                    if (!empty($filter['district'])) {
                        $query->where('district_id', $filter['district']);
                        $query2->where('district_id', $filter['district']);
                    }
					if (!empty($filter['city'])) {
                        $query->where('city_id', $filter['city']);
                        $query2->where('city_id', $filter['city']);
                    }
                    if (!empty($filter['colleges'])) {
                        $query->where('college_id', $filter['colleges']);
                        $query2->where('college_id', $filter['colleges']);
                    }
				
		}
		
		
                $student = $query->count();
                $tutor = $query2->count();
		
		
		
		return view('backend.reports.student-tutor-count', compact('student','tutor','states','zones','districts','cities','colleges'));
    }

}
