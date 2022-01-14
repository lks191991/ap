<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\District;
use App\Models\Zone;
use App\Models\State;
use App\Models\College;
use Illuminate\Validation\Rule;
use Validator;
use Illuminate\Support\Facades\Redirect;
use Auth;

class CollegeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       

        $query = State::where('status', '=', 1);


        $states = $query->orderBy('name')
                ->pluck('name', 'id');

        //get all colleges
        $colleges = College::orderBy('id', 'desc')->get();

        return view('backend.colleges.index', compact('colleges', 'states'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $states = State::where('status', '=', 1)->orderBy('name')->pluck('name', 'id');
        return view('backend.colleges.create', compact('states'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $city_id = $request->input('city_name');

      
        $validator = Validator::make($request->all(), [
					'state_name' => 'required',
                    'zone_name' => 'required',
                    'district_name' => 'required',
                    'city_name' => 'required',
                    'name' => [
                        'required',
                        'max:180',
                        Rule::unique('colleges')->where(function ($query) use($city_id) {
                                    return $query->where('city_id', $city_id);
                                })
                    ],
        ]);

        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            return Redirect::back()
                            ->withErrors($validator) // send back all errors to the form
                            ->withInput();
        }
		
		$college = new College();
		

        //form data is available in the request object
        
		$college->state_id = $request->input('state_name');
        $college->zone_id = $request->input('zone_name');
		$college->district_id = $request->input('district_name');
        $college->city_id = $request->input('city_name');
        $college->name = $request->input('name');
        $college->status = ($request->input('status') !== null) ? $request->input('status') : 0;

        $college->save();

        /* if (!empty($request->input('ajax_request'))) {
            return redirect()->route('backend.classes.show', $subject->class_id)->with('success', 'Subject created Successfully');
        } else { */
            return redirect()->route('backend.colleges.index')->with('success', 'College created Successfully');
        //}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subject = Subject::findOrFail($id);

        $classes_details = Classes::where('id', $subject->class_id)->first();

        $class = Classes::findOrFail($subject->class_id);
        $course = Course::findOrFail($class->course_id);

        return view('backend.subjects.show', compact('subject', 'class', 'course'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //Find the subject
        $college = College::find($id);
        $states = State::where('status', '=', 1)->orderBy('name')->pluck('name', 'id');
        $zones = Zone::where('status', 1)->where('id', $college->zone_id)->orderBy('zone_name')->pluck('zone_name','id');
        $districts = District::where('status', 1)->where('id', $college->district_id)->orderBy('name')->pluck('name','id');
        $cities = City::where('status', 1)->where('id', $college->city_id)->orderBy('city_name')->pluck('city_name','id');
        return view('backend.colleges.edit', compact('college', 'states', 'zones', 'districts', 'cities'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit_ajax(Request $request)
    {
        $id = $request->input('subject_id');
        $subject = Subject::find($id);

        return view('backend.subjects.edit-ajax', compact('subject'));
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
        $college = College::find($id);
        $city_id = $request->input('city_name');

      
        $validator = Validator::make($request->all(), [
					'state_name' => 'required',
                    'zone_name' => 'required',
                    'district_name' => 'required',
                    'city_name' => 'required',
                    'name' => [
                        'required',
                        'max:180',
                        Rule::unique('colleges')->where(function ($query) use($city_id, $id) {
                                    return $query->where('city_id', $city_id)->where('id', '<>', $id);
                                })
                    ],
        ]);
      
        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            return Redirect::back()
                            ->withErrors($validator) // send back all errors to the form
                            ->withInput();
        }

		
		
		
        $college->state_id = $request->input('state_name');
        $college->zone_id = $request->input('zone_name');
		$college->district_id = $request->input('district_name');
        $college->city_id = $request->input('city_name');
        $college->name = $request->input('name');
        $college->status = ($request->input('status') !== null) ? $request->input('status') : 0;

        $college->save();

       
            return redirect()->route('backend.colleges.index')->with('success', 'College Information Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $college = College::find($id);
       
        $college->delete();

            return redirect()->route('backend.colleges.index')->with('success', 'College Deleted Successfully');
    }

}
