<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\Zone;
use App\Models\District;
use App\Models\City;
use App\Models\College;
use Illuminate\Validation\Rule;
use Validator;
use Illuminate\Support\Facades\Redirect;
use Auth;
use App\Helpers\SiteHelpers;

class ZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = Zone::where('id','<>',0);
        
        $zones = $query->orderBy('id', 'desc')->get();
        
        return view('backend.zones.index', compact('zones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$query = Zone::where('status','=',1);
        $zones = $query->orderBy('zone_name')->get();

        $states = State::where('status', 1)->orderBy('name')->get();
        
        return view('backend.zones.create', compact('zones','states'));
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
		
        
		$state_id = $request->input('state_id');
        $zone_name = $request->input('zone_name');

        $validator = Validator::make($request->all(), [
                    'zone_name' => [
                        'required',
                        'max:180',
                        Rule::unique('zones')->where(function ($query) use($state_id,$zone_name) {
                                    return $query->where('zone_name', $zone_name)->where('state_id', $state_id);
                                })
                    ],
        ],[
         'zone_name.unique'   => "The zone name has already been taken in this state."
        ]);

        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            return Redirect::back()
                            ->withErrors($validator) // send back all errors to the form
                            ->withInput();
        }

        //Persist the school in the database
        //form data is available in the request object
        $zone = new Zone();
        //input method is used to get the value of input with its
        //name specified
        $zone->zone_name = $zone_name;
        $zone->state_id = $state_id;
		
		if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('subadmin')){
			$zone->status = ($request->input('status') !== null) ? $request->input('status') : 0;
		}
		


        $zone->save(); //persist the data
		
		
        return redirect()->route('backend.zones.index')->with('success', 'Zone Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!Auth::user()->hasAccessToSchool($id)){
            return redirect()->route('backend.dashboard');           
        }

        $school = School::findOrFail($id);
       
        $courses = Course::where('school_id', $id)->orderBy('id', 'desc')->get();
		
		$courses_count = SiteHelpers::dashboard_resource_count('courses', $id);
		$classes_count = SiteHelpers::dashboard_resource_count('classes', $id);
		$videos_count = SiteHelpers::dashboard_resource_count('videos', $id);
        $student_count = SiteHelpers::dashboard_resource_count('students', $id);
        $tutor_count = SiteHelpers::dashboard_resource_count('tutors', $id);
        
        return view('backend.school.show', compact('school', 'courses', 'courses_count', 'classes_count', 'videos_count', 'student_count', 'tutor_count'));        
    }

    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
	
        //Find the school
        $zone = Zone::find($id);
        $states = State::where('status', 1)->orderBy('name')->get();

        return view('backend.zones.edit', compact('zone', 'states'));
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
		$data = $request->all();
		
        $state_id = $request->input('state_id');
        $zone_name = $request->input('zone_name');

        $validator = Validator::make($request->all(), [
                    'zone_name' => [
                        'required',
                        'max:180',
                        Rule::unique('zones')->where(function ($query) use($state_id, $id,$zone_name) {
                                    return $query->where('state_id', $state_id)->where('zone_name', $zone_name)->where('id', '<>', $id);
                                })
                    ],
        ]);

        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            return Redirect::back()
                            ->withErrors($validator) // send back all errors to the form
                            ->withInput();
        }

        //Retrieve the school and update
        $zone = Zone::find($id);
        //echo "<pre>"; print_r($school); exit;
        $zone->zone_name = $request->input('zone_name');
		
		if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('subadmin')){
			$zone->status = ($request->input('status') !== null) ? $request->input('status') : 0;
		}
		

        $zone->save(); //persist the data
        return redirect()->route('backend.zones.index')->with('success', 'Zone Information Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		
		
        $zone = Zone::find($id);
		
		//delete all child records.
		//delete school
		if(isset($zone->id) && !empty($zone->id)) {
			
			
			
			$districts = District::where('zone_id', $zone->id)->select('id')->get();
			//delete course
			foreach($districts as $district) {
				if(isset($district->id) && !empty($district->id)){
					
					$cities = City::where('district_id', $district->id)->select('id')->get();
				foreach($cities as $city) {
					if(isset($city->id) && !empty($city->id)) {
						$colleges = College::where('city_id', $city->id)->select('id')->get();
						foreach($colleges as $college) {
							if(isset($college->id) && !empty($college->id)) {
								$college->delete();
							}
						}
						
			
						$city->delete();
					}
				}
				}
			}
			
		}
		
		$zone->delete();
        return redirect()->route('backend.zones.index')->with('success', 'Zone Deleted Successfully');
    }

}
