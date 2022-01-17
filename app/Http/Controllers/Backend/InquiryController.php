<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactInquiry;
use App\Models\Newsletter;
use Illuminate\Validation\Rule;
use Validator;
use Illuminate\Support\Facades\Redirect;
use Auth;

class InquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
      public function ContactInquiries(Request $request)
    {

        //SiteHelpers::updateUUID('periods');
		$filter = $request->all();
		
	
        
        $query = ContactInquiry::where('id','<>',0);
		if(isset($filter['emailinput']) && !empty($filter['emailinput']))
		{
			$query->where('email','=',$filter['emailinput']);
		}
		if(isset($filter['contactinput']) && !empty($filter['contactinput']))
		{
			$query->where('mobile_number','=',$filter['contactinput']);
		}
		
		if(isset($filter['your_name']) && !empty($filter['your_name']))
		{
			$query->where('your_name','LIKE',"%".$filter['your_name']."%");
		}
		
        
        
        $ContactInquiries = $query->orderBy('id', 'desc')->paginate(20);
		
		
		
		return view('backend.enquiry.contact-us', compact('ContactInquiries'));
    }

  public function newsletters(Request $request)
    {

        //SiteHelpers::updateUUID('periods');
		$filter = $request->all();
		
	
        
        $query = Newsletter::where('id','<>',0);
		if(isset($filter['emailinput']) && !empty($filter['emailinput']))
		{
			$query->where('email','=',$filter['emailinput']);
		}
		
		
        
        
        $newsletters = $query->orderBy('id', 'desc')->paginate(20);
		
		
		
		return view('backend.enquiry.subscription', compact('newsletters'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyContactInquiry($id)
    {
        $ContactInquiry = ContactInquiry::find($id);
		
        $ContactInquiry->delete();
        
        return redirect()->route('backend.ContactInquiries')->with('success', 'Data Deleted Successfully');
    }
	
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyNewsletters($id)
    {
        $Newsletter = Newsletter::find($id);
		
        $Newsletter->delete();
        
        return redirect()->route('backend.newsletters')->with('success', 'Data Deleted Successfully');
    }
    
}
