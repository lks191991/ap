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

class CitiesController extends Controller
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

        //get all classes
        $cities = City::orderBy('id', 'desc')->get();

        return view('backend.cities.index', compact('cities', 'states'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $states = State::where('status', '=', 1)->orderBy('name')
        ->pluck('name', 'id');
       
        return view('backend.cities.create', compact('states'));
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

        $districts_id = $data['district_name'];



            $validator = Validator::make($request->all(), [
                        'state_name' => 'required',
                        'zone_name' => 'required',
                        'district_name' => 'required',
                        'city_name' => [
                            'required',
                            'max:180',
                            Rule::unique('cities')->where(function ($query) use($districts_id) {
                                        return $query->where('district_id', $districts_id);
                                    })
                        ],
            ]);

        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            return Redirect::back()
                            ->withErrors($validator) // send back all errors to the form
                            ->withInput();
        }


        //form data is available in the request object
        $city = new City();

        $city->state_id = $request->input('state_name');
        $city->zone_id = $request->input('zone_name');
        $city->district_id = $request->input('district_name');
        $city->city_name = $request->input('city_name');
        $city->status = ($request->input('status') !== null) ? $request->input('status') : 0;

        $city->save();

        if (!empty($request->input('ajax_request'))) {
            return redirect()->route('backend.cities.show', $class->city)->with('success', 'City created Successfully');
        } else {
            return redirect()->route('backend.cities.index')->with('success', 'City created Successfully');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $class = Classes::findOrFail($id);
        $course = Course::where('id', $class->course_id)->select('school_id')->first();

        return view('backend.classes.show', compact('course', 'class'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      
       
        $city = City::find($id);
        $states = State::where('status', '=', 1)->orderBy('name')->pluck('name', 'id');
        $zones = Zone::where('status', 1)->where('id', $city->zone_id)->orderBy('zone_name')->pluck('zone_name','id');
        $districts = District::where('status', 1)->where('id', $city->district_id)->orderBy('name')->pluck('name','id');

        return view('backend.cities.edit', compact('city', 'states', 'zones', 'districts'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit_ajax(Request $request)
    {
         $districts_id = $data['district_name'];
        $class = Classes::find($id);

        return view('backend.classes.edit-ajax', compact('class'));
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
        $city = City::find($id);
        $districts_id = $data['district_name'];

        $validator = Validator::make($request->all(), [
            'state_name' => 'required',
            'zone_name' => 'required',
            'district_name' => 'required',
            'city_name' => [
                'required',
                'max:180',
                Rule::unique('cities')->where(function ($query) use($districts_id, $id) {
                            return $query->where('district_id', $districts_id)->where('id', '<>', $id);
                        })
            ],
]);
       

        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            return Redirect::back()
                            ->withErrors($validator) // send back all errors to the form
                            ->withInput();
        }


        $city->state_id = $request->input('state_name');
        $city->zone_id = $request->input('zone_name');
        $city->district_id = $request->input('district_name');
        $city->city_name = $request->input('city_name');
        $city->status = ($request->input('status') !== null) ? $request->input('status') : 0;

        $city->save();
       
            return redirect()->route('backend.cities.index')->with('success', 'City Information Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $city = City::find($id);
       
        if (isset($city->id) && !empty($city->id)) {
            $colleges = College::where('city_id', $city->id)->select('id')->get();
            foreach ($colleges as $college) {
                    $college->delete();
                }
            }


        $city->delete();

        if (!empty($request->input('ajax_request'))) {
            return redirect()->route('backend.course.show', $class->course_id)->with('success', 'Class Deleted Successfully');
        } else {
            return redirect()->route('backend.cities.index')->with('success', 'City Deleted Successfully');
        }
    }

}
