<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Coupon;
use Illuminate\Validation\Rule;
use Validator;
use Illuminate\Support\Facades\Redirect;
use Auth;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {	

		//Show all categories from the database and return to view
        $coupons = Coupon::orderBy('id', 'desc')->get();
		
		//pr($schools); 
		//echo json_encode($schools);
		//exit;
        return view('backend.coupons.index',compact('coupons'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		
        return view('backend.coupons.create');
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
        if($data['coupon_type']=='fixed')
        {
            $this->validate($request, [
                'coupon_type' => 'required',    
                'name' => 'required|max:180', 
                'coupon_value' => 'required|min:1', 
                'expired_at' => 'required',         
            ]);
        }

        if($data['coupon_type']=='percent')
        {
            $this->validate($request, [
                'coupon_type' => 'required',    
                'name' => 'required|max:180', 
                'coupon_value' => 'required|integer|between:1,100', 
                'expired_at' => 'required',         
            ]);
        }
		
			
       if($data['quantity']>1)
        {
            for($i=1;$i<=$data['quantity'];$i++)
            {
                $coupon = strtoupper(str_random(8));
                $category = new Coupon();
                $category->name = $request->input('name');
                $category->code = $coupon;
                $category->type = $request->input('coupon_type');
                $category->coupon_value = $request->input('coupon_value');
                $category->expired_at =\Carbon\Carbon::parse($request->input('expired_at'))->format('Y-m-d');
                $category->save(); //persist the data
            }
           
        }
       else
        {
            $coupon = strtoupper(str_random(8));
            $category = new Coupon();
            $category->name = $request->input('name');
            $category->code = $coupon;
            $category->type = $request->input('coupon_type');
            $category->coupon_value = $request->input('coupon_value');
            $category->expired_at =\Carbon\Carbon::parse($request->input('expired_at'))->format('Y-m-d');
            $category->save(); //persist the data
        }
      
		
		
        return redirect()->route('backend.coupons.index')->with('success','Coupons Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		

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
		
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$coupon = Coupon::find($id);
        $coupon->delete();
		return redirect()->route('backend.coupons.index')->with('success','Coupon Deleted Successfully');
    }
}
