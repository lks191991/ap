<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\Course;
use App\Models\SchoolCategory;
use App\Models\School;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Validation\Rule;
use Validator;
use Illuminate\Support\Facades\Redirect;
use Auth;

class ClassesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $classes = Classes::orderBy('id', 'desc')->get();

      
        return view('backend.classes.index', compact('classes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $courses = Course::where('status', '=', 1)->orderBy('name')
        ->pluck('name', 'id');


        return view('backend.classes.create', compact('courses'));
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

        $school_name = $request->input('class_name');
        $validator = Validator::make($request->all(), [
            'institute' => 'required',
            'class_name' => [
                'required',
                'max:180',
                Rule::unique('classes')->where(function ($query) use($school_name) {  
                    return $query->where('class_name', $school_name);
                })
            ]
        ],[
            'class_name.required' => "Shool Name required",
            'class_name.unique' => "The Shool name has already been taken",
        ]);

  // if the validator fails, redirect back to the form
  if ($validator->fails()) {
    return Redirect::back()
                    ->withErrors($validator) // send back all errors to the form
                    ->withInput();
}
        //form data is available in the request object
        $class = new Classes();

        $class->course_id = $request->input('institute');
        $class->class_name = $request->input('class_name');
        $class->status = ($request->input('status') !== null) ? $request->input('status') : 0;

        $class->save();

        if (!empty($request->input('ajax_request'))) {
            return redirect()->route('backend.course.show', $class->course_id)->with('success', 'School created Successfully');
        } else {
            return redirect()->route('backend.classes.index')->with('success', 'School created Successfully');
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
      
        $courses = Course::where('status', '=', 1)->orderBy('name')
        ->pluck('name', 'id');

        $class = Classes::find($id);


        return view('backend.classes.edit', compact('class', 'courses'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit_ajax(Request $request)
    {
        $id = $request->input('class_id');
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
        $class = Classes::find($id);
        $school_name = $request->input('class_name');

        $validator = Validator::make($request->all(), [
            'class_name' => [
                'required',
                'max:180',
                Rule::unique('classes')->where(function ($query) use($id,$school_name) {  
                    return $query->where('class_name', $school_name)->where('id', '<>', $id);
                })
            ]
        ],[
            'class_name.required' => "Shool Name required",
            'class_name.unique' => "The Shool name has already been taken",
        ]);


        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            return Redirect::back()
                            ->withErrors($validator) // send back all errors to the form
                            ->withInput();
        }


        $class->class_name = $request->input('class_name');
        $class->status = ($request->input('status') !== null) ? $request->input('status') : 0;

        $class->save();

        if (!empty($request->input('ajax_request'))) {
            return redirect()->route('backend.course.show', $class->course_id)->with('success', 'School Information Updated Successfully');
        } else {
            return redirect()->route('backend.classes.index')->with('success', 'School Information Updated Successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $class = Classes::find($id);
       
        if (isset($class->id) && !empty($class->id)) {
            $subjects = Subject::where('class_id', $class->id)->select('id')->get();
            foreach ($subjects as $subject) {
                if (isset($subject->id) && !empty($subject->id)) {
                    $topics = Topic::where('subject_id', $subject->id)->select('id')->get();
                    foreach ($topics as $topic) {
                        if (isset($topic->id) && !empty($topic->id))
                            $topic->delete();
                    }

                    $subject->delete();
                }
            }

        }

        $class->delete();

        if (!empty($request->input('ajax_request'))) {
            return redirect()->route('backend.course.show', $class->course_id)->with('success', 'School Deleted Successfully');
        } else {
            return redirect()->route('backend.classes.index')->with('success', 'School Deleted Successfully');
        }
    }

}
