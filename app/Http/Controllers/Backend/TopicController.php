<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Classes;
use App\Models\Course;
use App\Models\School;
use App\Models\SchoolCategory;
use Illuminate\Validation\Rule;
use Validator;
use Illuminate\Support\Facades\Redirect;
use Auth;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       
        $filter = $request->all();
        
        $schools = School::with('subject')->where('status', '=', 1)->orderBy('school_name')
                ->pluck('school_name', 'id');

        //get all topics
        $query = Topic::where('id','<>',0);
        if(isset($filter['school']) && !empty($filter['school']))
		{
			$query->whereHas('subject', function($q) use($filter){
                $q->where('school_id','=',$filter['school']);
            });

		}
		if(isset($filter['school_course']) && !empty($filter['school_course']))
		{
            $query->whereHas('subject', function($q) use($filter){
                $q->where('course_id','=',$filter['school_course']);
            });
		}
		
		if(isset($filter['subject']) && !empty($filter['subject']))
		{
            $query->whereHas('subject', function($q) use($filter){
                $q->where('id','=',$filter['subject']);
            });
		}

        if(isset($filter['topic_name']) && !empty($filter['topic_name']))
		{
			$query->where('topic_name','like','%'.trim($filter['topic_name']).'%');
		}

        $topics = $query->orderBy('id', 'desc')->paginate(20);

        return view('backend.topics.index', compact('topics', 'schools'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $schools = School::orderBy('school_name')->where('status', '=', 1)->pluck('school_name', 'id');
        $subjects = Subject::orderBy('subject_name')->where('status', '=', 1)->pluck('subject_name', 'id');

        return view('backend.topics.create', compact('subjects', 'schools'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $subject_id = $request->subject;
            $validator = Validator::make($request->all(), [
                        'subject' => 'required',
                        'course_type' => 'required',
                        'course' => 'required',
                        'topic_name' => [
                            'required',
                            'max:180',
                            Rule::unique('topics')->where(function ($query) use($subject_id) {
                                        return $query->where('subject_id', $subject_id);
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
        $topic = new Topic();

        $topic->subject_id = $request->subject;
        $topic->topic_name = $request->input('topic_name');
        $topic->status = ($request->input('status') !== null) ? $request->input('status') : 0;

        $topic->save();
      return redirect()->route('backend.topics.index')->with('success', 'Topic created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $topic = Topic::findOrFail($id);
        return redirect()->route('backend.topics.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
        //Find the employee
        $topic = Topic::find($id);

        $subject_details = Subject::where("id", $topic->subject_id)->first();
        $topic->school_id = $subject_details->school_id;
        $topic->course_id = $subject_details->course_id;
        $topic->school_id = $subject_details->school_id;

        $schools = School::orderBy('school_name')->where('status', '=', 1)->pluck('school_name', 'id');
        $courses = Course::where('school_id', $topic->school_id)->orderBy('name')->where('status', '=', 1)->pluck('name', 'id');
        $subjects = Subject::where('course_id', $topic->course_id)->orderBy('subject_name')->where('status', '=', 1)->pluck('subject_name', 'id');

        return view('backend.topics.edit', compact('topic', 'subjects', 'schools', 'courses'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit_ajax(Request $request)
    {
        $id = $request->input('topic_id');
        $topic = Topic::find($id);

        return view('backend.topics.edit-ajax', compact('topic'));
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
        $subject_id = $request->subject;

        $subject_details = Subject::where('id', $subject_id)->first();
	
        $course_id = $subject_details->subject_class->course_id;

        $topic = Topic::find($id);

     
            $validator = Validator::make($request->all(), [
                         'subject' => 'required',
                        'course_type' => 'required',
                        'course' => 'required',
                        'topic_name' => [
                            'required',
                            'max:180',
                            Rule::unique('topics')->where(function ($query) use($subject_id, $id) {
                                        return $query->where('subject_id', $subject_id)->where('id', '<>', $id);
                                    })
                        ],
            ]);


        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            return Redirect::back()
                            ->withErrors($validator) // send back all errors to the form
                            ->withInput();
        }

        $topic->subject_id = $request->subject;
        $topic->topic_name = $request->input('topic_name');
        $topic->status = ($request->input('status') !== null) ? $request->input('status') : 0;
        $topic->save(); //persist the data

        if (!empty($request->input('ajax_request'))) {
            return redirect()->route('backend.subjects.show', $topic->subject_id)->with('success', 'Topic Information Updated Successfully');
        } else {
            return redirect()->route('backend.topics.index')->with('success', 'Topic Information Updated Successfully');
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
        $topic = Topic::find($id);

        if (isset($topic->id) && !empty($topic->id)) {
            $videos = Video::where('topic_id', $topic->id)->select('id')->get();
            foreach ($videos as $video) {
                if (isset($video->id) && !empty($video->id))
                    $video->delete();
            }
        }

        $topic->delete();

        if (!empty($request->input('ajax_request'))) {
            return redirect()->route('backend.subjects.show', $topic->subject_id)->with('success', 'Topic Deleted Successfully');
        } else {
            return redirect()->route('backend.topics.index')->with('success', 'Topic Deleted Successfully');
        }
    }
    
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveOrdering(Request $request)
    {
        $weights = $request->get('weight');
        foreach ($weights as $id => $weight) {
            Topic::where('id', '=', $id)->update(['weight' => $weight]);
        }
        exit;
    }

}
