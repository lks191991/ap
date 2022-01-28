@extends('backend.layouts.layout-2')

@section('styles')

@endsection
@section('scripts')

@endsection

@section('content')
<h4 class="font-weight-bold py-3 mb-4">
    <span class="text-muted font-weight-light">Payments /</span> Payment Details
</h4>

<div class="card mb-4">   
    
    <h6 class="card-header">

		<a href="javascript:void(0)" onclick="window.history.go(-1); return false;" class="btn btn-primary rounded-pill d-block detail-back-btn">Back</a>

    </h6>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-6 col-xl-7">
            <div class="row">
                    <div class="col-sm-6 col-xl-3 mb-2"><strong>Date</strong></div>
                    <div class="col-sm-6 col-xl-9">{{date("d-m-Y", strtotime($payment->created_at))}}</div>
                </div>
			<div class="row">
                    <div class="col-sm-6 col-xl-3 mb-2"><strong>Student Name</strong></div>
                    <div class="col-sm-6 col-xl-9">{{$payment->user->name}}</div>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-xl-3 mb-2"><strong>Student Email</strong></div>
                    <div class="col-sm-6 col-xl-9">{{$payment->user->email}}</div>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-xl-3 mb-2"><strong>Student Contact</strong></div>
                    <div class="col-sm-6 col-xl-9">{{$payment->user->mobile}}</div>
                </div>
               
                
               
                <div class="row">
                    <div class="col-sm-6 col-xl-3 mb-2"><strong>Course</strong></div>
                    <div class="col-sm-6 col-xl-9">{{$payment->course->name}}</div>
                </div>
              
                <div class="row">
                    <div class="col-sm-6 col-xl-3 mb-2"><strong>Actual Price</strong></div>
                    <div class="col-sm-6 col-xl-9">{{$payment->actual_price}}</div>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-xl-3 mb-2"><strong>Actual Payment</strong></div>
                    <div class="col-sm-6 col-xl-9">{{$payment->price}}</div>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-xl-3 mb-2"><strong>Discount</strong></div>
                    <div class="col-sm-6 col-xl-9">{{$payment->discount}}</div>
                </div>

                <div class="row">
                    <div class="col-sm-6 col-xl-3 mb-2"><strong>Code</strong></div>
                    <div class="col-sm-6 col-xl-9">{{$payment->code}}</div>
                </div>
             
            </div>
            
            
        </div>		
    </div>
</div>

@endsection