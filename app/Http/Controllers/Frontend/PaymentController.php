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
		
		if(!$data['cid'])
		{
		return redirect()->route('course-details',[$data['cid']])->with('error', 'Something went wrong.');
		}
		
		$course = Course::where('uuid', '=', $data['cid'])->where('status', '=', 1)->first();
		

		$paymentGt = 1;
		if(Session::has('newPrice'))
		{
			
				$price = Session::get('newPrice');
		
		}
		else{
			$price = $course->course_price;
		}
		
			if($price > 0)
			{
			$paymentGt = 1;
			}
			else{
			$paymentGt = 0;
			}

		
		
		
		$userSubscription = UserSubscription::where("user_id",Auth::user()->id)->where("course_id",$course->id)->count();
		if($userSubscription > 0)
		{
		return redirect()->route('course-details',[$subject->id])->with('error', 'Course already in your learning aacount.');
		}
		return view('frontend.payment',compact('course','paymentGt'));
    }
	
	
	
	public function paymentPost(Request $request)
	{
		$data = $request->all();
		$user = Auth::user();
		$course = Course::where('uuid', '=', $data['cid'])->where('status', '=', 1)->orderBy('created_at','DESC')->first();
		$paymentGt = 1;
		if(Session::has('newPrice'))
		{
			$price = Session::get('newPrice');
			$discount = Session::get('discount');
			$code = Session::get('code');
			$coupon_id = Session::get('coupon_id');
		}
		else{
			$price = $course->course_price;
			$discount = 0;
			$code = '';
			$coupon_id = '';
		}

			if($price > 0)
			{
			$paymentGt = 1;
			}
			else{
			$paymentGt = 0;
			}
		
		if($paymentGt==1)
		{
			$amount = $price * 100;
			Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
			$customer = \Stripe\Customer::create(array( 
				'name' => $user->name,
				'email' => $user->email,
				'description' => $course->name,        
				'source'  => $request->stripeToken ,
				'address' => [
					'line1' => '510 Townsend St',
					'postal_code' => '98140',
					'city' => 'San Francisco',
					'state' => 'CA',
					'country' => 'US',
				  ],
		));
		$orderID = strtoupper(str_replace('.','',uniqid('', true))); 
		$result = \Stripe\Charge::create(array( 
			'customer' => $customer->id, 
			'amount'   => $amount, 
			'currency' => "usd", 
			'description' => $course->name, 
			'metadata' => array( 
				'order_id' => $orderID 
			) 
		));
			
		}
		else
		{
			$result['status'] = 'succeeded';
			$result['id'] = "Offer";
		}
		
		
		$payment = new Payment();
		$userSubscription = new UserSubscription();

		if($result['status'] == 'succeeded')
		{
			$payment->transaction_id =  $result['id'];
			$payment->amount = $price;
			$payment->payment_status = 'Success';
			if($payment->save())
			{
					$userSubscription->user_id = Auth::user()->id;
					$userSubscription->course_id = $course->id;
					$userSubscription->payment_id = $payment->id;
					$userSubscription->actual_price = $course->course_price;
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
				
				return redirect()->route('frontend.paymentSuccess')->with('success', 'your payment has been successfully processed.');
			}
		}
		else
		{
			$payment->transaction_id = $result['id'];
			$payment->amount = $result['id'];
			$payment->payment_status = 'Faild';
			$payment->save();
			return redirect()->route('frontend.paymentFaild')->with('error', 'Course not available currently');
		}
		
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
		$data = UserSubscription::with('course','user','payment')->where("user_id",$user->id)->paginate(20);
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
			$course = Course::where('uuid', '=', $data['cid'])->where('status', '=', 1)->orderBy('created_at','DESC')->first();
			
			$coursePrice = $course->course_price;
			$dateExpired = date('Y-m-d');
        	$coupon = Coupon::where("code",$data['code'])->whereDate('expired_at', '>=', $dateExpired)->first();
        	
			if(isset($coupon) && !empty($coupon))
			{
				if($coupon->type == 'fixed'){
					$coupon_total =  $coupon->coupon_value;
				} elseif ($coupon->type == 'percent'){
					$coupon_total = ($coupon->coupon_value / 100) * $coursePrice;
				} else{
					$coupon_total = 0;
				}

				

				if($coupon_total >= $coursePrice)
				{
					$newPrice = 0;
					session(['newPrice' => 0,'discount' => $coursePrice,'code' => $data['code'],'coupon_id' => $coupon->id]);
				}
				else
				{
					$newPrice = $coursePrice-$coupon_total;
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
			$course = Course::where('uuid', '=', $data['cid'])->where('status', '=', 1)->first();
			$coursePrice = $course->course_price;
		
			session()->forget('newPrice');
			session()->forget('discount');
			session()->forget('code');
			session()->forget('coupon_id');
				return response()->json([
					"status"=>200,
					"message"=>"Coupon code removed successfully.",
					'data' => ['newPrice' => $coursePrice,
					'code' => $data['code']
					]
				]); 


	}
	
}
