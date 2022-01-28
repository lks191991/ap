<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SchoolCategory;
use App\Models\Subject;
use App\Models\Course;
use App\Models\Classes;
use App\Models\School;
use App\Models\Topic;
use Illuminate\Validation\Rule;
use Validator;
use Illuminate\Support\Facades\Redirect;
use Auth;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       

        $query = School::where('status', '=', 1);


        $schools = $query->orderBy('school_name')
                ->pluck('school_name', 'id');

        //get all subjects
        $subjects = Subject::orderBy('id', 'desc')->get();

        return view('backend.subjects.index', compact('subjects', 'schools'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $institutes = SchoolCategory::orderBy('name')->where('status', '=', 1)->pluck('name', 'id');
        $schools = School::orderBy('school_name')->where('status', '=', 1)->pluck('school_name', 'id');
        return view('backend.subjects.create', compact('institutes','schools'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $course_id = $request->input('course');

      
        $validator = Validator::make($request->all(), [
                    'subject_name' => [
                        'required',
                        'max:180',
                        Rule::unique('subjects')->where(function ($query) use($course_id) {
                                    return $query->where('course_id', $course_id);
                                })
                    ],
        ]);

        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            return Redirect::back()
                            ->withErrors($validator) // send back all errors to the form
                            ->withInput();
        }
		
		$subject = new Subject();
		 /** Below code for save banner_image * */
        if ($request->hasFile('banner_image')) {

            $validator = Validator::make($request->all(), [
                        'banner_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                            ], [
                        'banner_image.max' => 'The banner image may not be greater than 2 mb.',
            ]);

            if ($validator->fails()) {
                return redirect()->route('backend.subjects.create')->withErrors($validator)->withInput();
            }

            $destinationPath = public_path('/uploads/subject/');
            $newName = '';
            $fileName = $request->all()['banner_image']->getClientOriginalName();
            $file = request()->file('banner_image');
            $fileNameArr = explode('.', $fileName);
            $fileNameExt = end($fileNameArr);
            $newName = date('His') . rand() . time() . '__' . $fileNameArr[0] . '.' . $fileNameExt;

            $file->move($destinationPath, $newName);

            $imagePath = 'uploads/subject/' . $newName;
            $subject->banner_image = $imagePath;
        }
		

        //form data is available in the request object
        
		$subject->course_id = $course_id;
        $subject->subject_name = $request->input('subject_name');
        $subject->school_id = $request->input('course_type');
		$subject->subject_price = 0;
        $subject->class_id = 1;
        $subject->status = ($request->input('status') !== null) ? $request->input('status') : 0;

        $subject->save();

      
            return redirect()->route('backend.subjects.index')->with('success', 'Subject created Successfully');
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
        $subject = Subject::find($id);

        $institutes = SchoolCategory::orderBy('name')->where('status', '=', 1)->pluck('name', 'id');

        $course_details = Course::where("id", $subject->course_id)->select('school_id')->where('status', '=', 1)->first();
       

        $schools = School::orderBy('school_name')->where('status', '=', 1)->pluck('school_name', 'id');
        $courses = Course::orderBy('name')->where('status', '=', 1)->pluck('name', 'id');

        return view('backend.subjects.edit', compact('subject', 'institutes', 'schools', 'courses'));
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
        $subject = Subject::find($id);
        $course_id = $request->input('course');



        $validator = Validator::make($request->all(), [
                    'subject_name' => [
                        'required',
                        'max:180',
                        Rule::unique('subjects')->where(function ($query) use($course_id, $id) {
                                    return $query->where('course_id', $course_id)->where('id', '<>', $id);
                                })
                    ],
        ]);

        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            return Redirect::back()
                            ->withErrors($validator) // send back all errors to the form
                            ->withInput();
        }

			if ($request->hasFile('banner_image')) {

            $validator = Validator::make($request->all(), [
                        'banner_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                            ], [
                        'banner_image.max' => 'The banner image may not be greater than 2 mb.',
            ]);

            if ($validator->fails()) {
                return redirect()->route('backend.subjects.edit', $id)->withErrors($validator)->withInput();
            }

            $destinationPath = public_path('/uploads/subject/');
            $newName = '';
            $fileName = $request->all()['banner_image']->getClientOriginalName();
            $file = request()->file('banner_image');
            $fileNameArr = explode('.', $fileName);
            $fileNameExt = end($fileNameArr);
            $newName = date('His') . rand() . time() . '__' . $fileNameArr[0] . '.' . $fileNameExt;

            $file->move($destinationPath, $newName);

            $oldImage = public_path($subject->banner_image);
            //echo $oldImage; exit;
            if (!empty($subject->banner_image) && file_exists($oldImage)) {
                unlink($oldImage);
            }

            $imagePath = 'uploads/subject/' . $newName;
            $subject->banner_image = $imagePath;
        }
		
		
        $subject->course_id = $course_id;
        $subject->subject_name = $request->input('subject_name');
		$subject->subject_price = 0;
        $subject->school_id = $request->input('course_type');
        $subject->class_id = 1;
        $subject->status = ($request->input('status') !== null) ? $request->input('status') : 0;
        $subject->save(); //persist the data

       
            return redirect()->route('backend.subjects.index')->with('success', 'Subject Information Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $subject = Subject::find($id);

        $classes_details = Classes::where('id', $subject->class_id)->first();

        if (!Auth::user()->hasAccessToSchool($classes_details->course->school_id)) {
            return redirect()->route('backend.dashboard');
        }

        if (isset($subject->id) && !empty($subject->id)) {
            $topics = Topic::where('subject_id', $subject->id)->select('id')->get();
            foreach ($topics as $topic) {
                if (isset($topic->id) && !empty($topic->id))
                    $topic->delete();
            }
        }

        $subject->delete();

            return redirect()->route('backend.subjects.index')->with('success', 'Subject Deleted Successfully');
    }

}
