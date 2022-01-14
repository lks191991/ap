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

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {	

			$districts = District::orderBy('id', 'desc')->get();
        
		
		//pr($schools); 
		//echo json_encode($schools);
		//exit;
        return view('backend.districts.index',compact('districts'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($school_id = '')
    {
		
		$query = State::where('status','=',1);
       
        $states = $query->orderBy('name')
                        ->pluck('name','id');
		
		
		$zones = Zone::where('status', 1)->orderBy('zone_name')
        ->pluck('zone_name','id');
			
		return view('backend.districts.create', compact('states', 'zones'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		 //Persist the City in the database
        //form data is available in the request object
        $district = new District();
		$state_id = $request->input('state_name');
        $zone_id = $request->input('zone_name');
		$name = $request->input('name');
		
			
			$validator = Validator::make($request->all(), [
				'state_name' => 'required',
				'zone_name' => 'required',
				'name' => [
					'required',
					'max:180',
					Rule::unique('districts')->where(function ($query) use($state_id,$zone_id,$name) {  
						return $query->where('name', $name)->where('state_id', $state_id)->where('zone_id', $zone_id);
					})
				],
				
			]);
		
		if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator) // send back all errors to the form
                ->withInput();
        }
		
		
       //input method is used to get the value of input with its
        //name specified
		$district->name = $request->input('name');
		$district->state_id = $state_id;
		$district->zone_id = $zone_id;
		$district->status = ($request->input('status') !== null)? $request->input('status'):0;
		$district->save(); //persist the data
		
			return redirect()->route('backend.districts.index')->with('success','District Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		$district = District::findOrFail($id);
		
		
		return view('backend.districts.show', compact('district'));
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
	
       //Find the course
        $district = District::find($id);
		
		$query = State::where('status','=',1);
       
        $states = $query->orderBy('name')
                        ->pluck('name','id');
		
		
		$zones = Zone::where('status', 1)->orderBy('zone_name')
        ->pluck('zone_name','id');
		
		return view('backend.districts.edit',compact('district', 'states', 'zones'));

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
        $district = District::find($id);
		
            $state_id = $request->input('state_name');
            $zone_id = $request->input('zone_name');
            $name = $request->input('name');
		
			
			$validator = Validator::make($request->all(), [
				//'institute_type' => 'required',
				//'school_name' => 'required',
				'name' => [
					'required',
					'max:180',
                    Rule::unique('districts')->where(function ($query) use($state_id,$zone_id,$name,$id) {  
						return $query->where('name', $name)->where('state_id', $state_id)->where('zone_id', $zone_id)->where('id','<>', $id);
					})
				
				],
				
			]);
		
		if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator) // send back all errors to the form
                ->withInput();
        }
		
		
		$district->name = $request->input('name');
		$district->state_id = $state_id;
		$district->zone_id = $zone_id;
		$district->status = ($request->input('status') !== null)? $request->input('status'):0;
        $district->save(); //persist the data
		
        return redirect()->route('backend.districts.index')->with('success','District Information Updated Successfully');
		
        

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
		$district = District::find($id);
		
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
		
        $district->delete();
		
			return redirect()->route('backend.districts.index')->with('success','District Deleted Successfully');
	}
}
