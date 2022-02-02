<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Payment;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\Tutor;
use App\Models\Subject;
use App\Models\Coupon;
use App\Models\UserSubscription;
use App\User;
use Carbon\Carbon;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Redirect;
use Session;
use Stripe;
use Validator;
use Auth;
use Exception;
use Illuminate\Support\Facades\Response;

class PaymentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show Student profile.
     *
     */
    public function index(Request $request)
    {
		$data = $request->all();
		$userId = Auth::user()->id;
		$user = User::where('id', $userId)->first()->toArray();

		if(!$data['sid'])
		{
		return redirect()->route('course-details',[$subject->id])->with('error', 'Something went wrong.');
		}
		
		$subject = Subject::with('topics','subject_class')->where('uuid', '=', $data['sid'])->where('status', '=', 1)->orderBy('created_at','DESC')->first();
		
		
		if(!$subject)
		{
		return redirect()->route('course-details',[$subject->id])->with('error', 'Something went wrong.');
		}
		
		$userSubscription = UserSubscription::where("user_id",Auth::user()->id)->where("course_id",$subject->course_id)->where("subject_id",$subject->id)->count();
		if($userSubscription > 0)
		{
		return redirect()->route('course-details',[$subject->id])->with('error', 'Course already in your learning aacount.');
		}

		$razorpayShow=0;

		if(Session::has('newPrice'))
		{
			$price = Session::get('newPrice');
			$amt_rpay = Session::get('newPrice')*100;
			$discount = Session::get('discount');
			$code = Session::get('code');
			$coupon_id = Session::get('coupon_id');
				
		}
		else{
			$amt_rpay = $subject->subject_price*100;
			$price = $subject->subject_price;
			$discount = 0;
			$code = '';
			$coupon_id = '';

		}

			if($price > 0)
			{
				$userData = [
					"name" =>$user['name'],
					"email" =>$user['email'],
					"contact" =>$user['mobile'],
					"user_id" =>$userId,
					"amt" =>$price,
					"discount" =>$discount
					];
					$client = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
					$payment  = $client->order->create([
					'receipt'         => 'order_rcptid_11',
					'amount'          => $amt_rpay,
					'currency'        => 'INR',
					'payment_capture' =>  '1'
					]);
	
					$paymentid = $payment->id;
					$razorpayShow = 1;
			}
			else{
				$userData = [
					"name" =>$user['name'],
					"email" =>$user['email'],
					"contact" =>$user['mobile'],
					"user_id" =>$userId,
					"amt" =>$price,
					"discount" =>0
	
					];
					
					$paymentid = 0;
					$razorpayShow = 0;
			}



		return view('frontend.payment',compact('subject','paymentid','amt_rpay','userData','razorpayShow'));
		

		
    }
	
	
	
	public function paymentPost(Request $request)
	{
		$data = $request->all();
		$subject = Subject::with('topics','subject_class')->where('uuid', '=', $data['sid'])->where('status', '=', 1)->orderBy('created_at','DESC')->first();
		$payment = new Payment();
		$userSubscription = new UserSubscription();
		if(Session::has('newPrice'))
		{
			$price = Session::get('newPrice');
			$discount = Session::get('discount');
			$code = Session::get('code');
			$coupon_id = Session::get('coupon_id');
		}
		else{
			$price = $subject->subject_price;
			$discount = 0;
			$code = '';
			$coupon_id = '';
		}
			$payment->transaction_id = $data['razorpay_payment_id'] ;// $result['id'];
			$payment->amount = $price;
			$payment->payment_status = $data['status_payment'];
			if($payment->save())
			{
				
				if($data['status_payment']=='Success')
				{
					$userSubscription->user_id = Auth::user()->id;
					$userSubscription->course_id = $subject->course_id;
					$userSubscription->subject_id = $subject->id;
					$userSubscription->payment_id = $payment->id;
					$userSubscription->actual_price = $subject->subject_price;
					$userSubscription->price = $price;
					$userSubscription->discount = $discount;
					$userSubscription->code = $code;
					$userSubscription->status = 'Success';
					$userSubscription->save();
					
					if(!empty($coupon_id))
					{
						$coupon = Coupon::find($coupon_id);
						$coupon->delete();
						session()->forget('newPrice');
						session()->forget('discount');
						session()->forget('code');
						session()->forget('coupon_id');
					}

					$response = [
						'status' => 'success',
					];
					
				}
				else
				{
					$response = [
						'status' => 'Failed',
					];
				}
				
				
			}
			else{
				$response = [
					'status' => 'Failed',
				];
				
			}
		
	
           
	
		
        return Response::json($response);
        
	}

	public function paymentSuccess(Request $request)
    {
		return view('frontend.payment-success');
	}
	
	public function paymentFaild(Request $request)
    {
		return view('frontend.payment-faild');
	}
	
	
	
	public function myPayment(Request $request)
    {
		$user = Auth::user();
		$data = UserSubscription::with('course','subject','user','payment')->where("user_id",$user->id)->paginate(20);
		return view('frontend.my-payment',compact('data'));
	}

	public function applyCoupon(Request $request)
    {
		$data = $request->all();
		session(['newPrice' => 0,'discount' => 0,'code' => '','coupon_id'=>'']);
		if(!empty($data['code']))
		{

			$user = Auth::user();
			$dateExpired = date('d-m-Y');
			$subject = Subject::with('topics','subject_class')->where('uuid', '=', $data['sid'])->where('status', '=', 1)->orderBy('created_at','DESC')->first();
			
			$subjectPrice = $subject->subject_price;
			$dateExpired = date('Y-m-d');
        	$coupon = Coupon::where("code",$data['code'])->whereDate('expired_at', '>=', $dateExpired)->first();
        	
			if(isset($coupon) && !empty($coupon))
			{
				if($coupon->type == 'fixed'){
					$coupon_total =  $coupon->coupon_value;
				} elseif ($coupon->type == 'percent'){
					$coupon_total = ($coupon->coupon_value / 100) * $subjectPrice;
				} else{
					$coupon_total = 0;
				}

				

				if($coupon_total >= $subjectPrice)
				{
					$newPrice = 0;
					session(['newPrice' => 0,'discount' => $subjectPrice,'code' => $data['code'],'coupon_id' => $coupon->id]);
				}
				else
				{
					$newPrice = $subjectPrice-$coupon_total;
					session(['newPrice' => $newPrice,'discount' => $coupon_total,'code' => $data['code'],'coupon_id' => $coupon->id]);
				}

				return response()->json([
					"status"=>200,
					"message"=>"Coupon code applied successfully.",
					'data' => ['newPrice' => $newPrice,
					'code' => $data['code']
					]
					]); 

				
			}
			else
			{
				return response()->json([
					"status"=>401,
					"message"=>"Please Enter Valid Coupon Code.",
					'data' => []
					]); 
			}

			
		}
		else
		{
			return response()->json([
				"status"=>401,
				"message"=>"Please Enter Valid Coupon Code",
				'data' => []
				]); 
		}

		

	}

	public function removeCoupon(Request $request)
    {
		$data = $request->all();
		

			$user = Auth::user();
			$dateExpired = date('d-m-Y');
			$subject = Subject::with('topics','subject_class')->where('uuid', '=', $data['sid'])->where('status', '=', 1)->orderBy('created_at','DESC')->first();
			$subjectPrice = $subject->subject_price;
		
			session()->forget('newPrice');
			session()->forget('discount');
			session()->forget('code');
			session()->forget('coupon_id');
				return response()->json([
					"status"=>200,
					"message"=>"Coupon code removed successfully.",
					'data' => ['newPrice' => $subjectPrice,
					'code' => $data['code']
					]
				]); 


	}
	
}
