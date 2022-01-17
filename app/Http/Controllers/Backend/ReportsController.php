<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentFavourites;
use App\Models\StudentVideo;
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
}
