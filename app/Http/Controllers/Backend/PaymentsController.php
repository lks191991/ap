<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserSubscription;
use App\Models\Payment;
use Validator;
use Illuminate\Support\Facades\Redirect;
use Auth;
use SiteHelpers;
use Illuminate\Support\Facades\Storage;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        //SiteHelpers::updateUUID('periods');
		$filter = $request->all();
	
        $query = UserSubscription::where('id','<>',0);
	
		if(isset($filter['emailinput']) && !empty($filter['emailinput']))
		{
            $query->whereHas('user', function($q) use($filter){
                $q->where('email','=',$filter['emailinput']);
            });
		}
        if(isset($filter['contactinput']) && !empty($filter['contactinput']))
		{
            $query->whereHas('user', function($q) use($filter){
                $q->where('mobile','=',$filter['contactinput']);
            });
		}
        if(isset($filter['your_name']) && !empty($filter['your_name']))
		{
            $query->whereHas('user', function($q) use($filter){
                $q->where('name','LIKE',"%".$filter['your_name']."%");
            });
		}
	
       
        
        $payments = $query->orderBy('id', 'desc')->paginate(20);
				
		
		return view('backend.payments.index', compact('payments'));
    }

    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        
        $payment = UserSubscription::find($uuid);
		        
        
        return view('backend.payments.show', compact('payment'));
    }

    
    public function failedPayments(Request $request)
    {

        //SiteHelpers::updateUUID('periods');
		$filter = $request->all();
		
		
        $query = Payment::where('payment_status','=','Success');
		
		if(isset($filter['date']) && !empty($filter['date']))
		{
            $start = Carbon::parse($filter['date']);
                $query->whereDate('created_at','=',$start->format('Y-m-d'));
		}
        if(isset($filter['transaction_id']) && !empty($filter['transaction_id']))
		{
            $query->where('transaction_id','=',$filter['transaction_id']);
		}
        if(isset($filter['amount']) && !empty($filter['amount']))
		{
            $query->where('amount','=',$filter['amount']);
		}
	
        
        $payments = $query->orderBy('id', 'desc')->paginate(20);
				
		return view('backend.payments.failed', compact('payments'));
    }
		
}
