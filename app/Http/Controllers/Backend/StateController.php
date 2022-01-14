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

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {	
		//Show all states from the database and return to view
        $states = State::orderBy('id', 'desc')->get();
		
		//pr($schools); 
		//echo json_encode($schools);
		//exit;
        return view('backend.states.index',compact('states'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
	
        return view('backend.states.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$this->validate($request, [
                'name' => 'required|unique:states,name|max:180',            
            ]);
			
       //Persist the state in the database
        //form data is available in the request object
        $state = new State();
        //input method is used to get the value of input with its
        //name specified
		$state->name = $request->input('name');
		$state->status = ($request->input('status') !== null)? $request->input('status'):0;
		
		$state->save(); //persist the data
        return redirect()->route('backend.states.index')->with('success','State Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
		$state = State::findOrFail($id);
		print_r($state);die;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		
       //Find the category
        $state = State::find($id);
		return view('backend.states.edit',compact('state'));

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
		$validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'max:180',
                Rule::unique('states')->where(function ($query) use($id) {  
                    return $query->where('id','<>', $id);
                })
            ],
            
        ]);
                                
        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator) // send back all errors to the form
                ->withInput();
        }
		
        //Retrieve the category and update
        $state = state::find($id);
		
		
		
		
		//echo "<pre>"; print_r($school); exit;
        $state->name = $request->input('name');
		$state->status = ($request->input('status') !== null)? $request->input('status'):0;
		$state->save(); //persist the data
        return redirect()->route('backend.states.index')->with('success','State Information Updated Successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$state = State::find($id);
		if(isset($state->id) && !empty($state->id)) {
				
				$zones = Zone::where('state_id', $state->id)->select('id')->get();
				foreach($zones as $zone) {
					if(isset($zone->id) && !empty($zone->id)) {
			
                        $districts = District::where('zone_id', $zone->id)->select('id')->get();
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
				}
				
				
				
				
			}
		
        $state->delete();
		return redirect()->route('backend.states.index')->with('success','Institution Deleted Successfully');
    }
}
