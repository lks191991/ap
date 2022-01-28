@extends('frontend.layouts.app')
@section('styles')
<style type="text/css">
        .panel-title {
        display: inline;
        font-weight: bold;
        }
        .display-table {
            display: table;
        }
        .display-tr {
            display: table-row;
        }
        .display-td {
            display: table-cell;
            vertical-align: middle;
            width: 61%;
        }
	.hide{
	display:none;}
	.paymentdiv{
        background-color: #1eb0e9;
        box-shadow: rgb(0 0 0 / 37%) 0px 0px 0.625rem 0px;
        transition: opacity 57ms ease 0s, transform 64ms ease 0s;
        border-radius: 0.1875rem;
    }
    </style>
	@endsection('styles')
@section('content')

<!-- Breadcrumbs -->

	<section class="breadcrumbs bg-light py-3">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<ul class="m-0 p-0 list-unstyled d-flex flex-wrap">
						<li>
							<a href="/">Home</a>
						</li>
						<li>
							<span class="mx-2">></span>Payment
						</li>
					</ul>
				</div>
			</div>
		</div>
	</section>

	<!-- Breadcrumbs Ends-->

	<!-- Order Summary -->

    <section class="order-summary section-padding border-top">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h1 class="page_title">Order Summary</h1>
                    <p class="description"><span class="today_date">{{date('d-M-Y')}}</span></p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="order-summary-main-table">
                        <div class="table-responsive">
                            <table class="table custom-table">
                                <thead>
                                    <tr>
                                        <th scope="col">Course Type</th>
                                        <td>{{$course->school->school_name}}</td>
                                        </tr>
                                        <tr>
                                        <th scope="col">Course</th>
                                        <td>{{$course->name}}</td>
                                        </tr>
                                        <tr>
                                        <th scope="col">Price</th>
                                        <td>{!!Config::get('constants.currency')!!}@if($course->course_price==0) Free @else {{$course->course_price}} @endif</td>
                                    </tr>
                                </thead>
                                
                                <tfoot>
                                    <tr>
                                        <th>Apply Coupon Code</th>
                                        <td >
                                            @if(session('discount')==0)
                                            <div id="coupon_input_box" class="row">
                                                <div class="col-md-7"><input type="text" autocomplete="off" class="form-control"  id="coupon_code" value="" />
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-primary btn-sm ms-md-5 ms-3" id="coupon_code_button" value="Pay">Apply</button>
                                                </div>
                                            </div>
                                            @else
                                            <div id="coupon_apply_box" class="row">
                                                <div class="col-md-12"  style="color:green"><span id="applied_code">{{session('code')}}</span><button type="button" class="btn btn-danger ms-md-5 ms-3 btn-sm " id="coupon_code_remove_button" >Remove</button></div>
                                               
                                            </div>
                                            @endif
                                            
                                            
                                    </td>
                                    </tr>
                                    <tr>
                                        <th>Total</th>
                                        @if(session('discount')==0)
                                        <td><b>{!!Config::get('constants.currency')!!}<span id="total_price">{{$course->course_price}}</span></td>
                                        @else
                                        <td><b>{!!Config::get('constants.currency')!!}<span id="total_price">{{session('newPrice')}}</span></td>
                                        @endif
                                    </tr>
                                </tfoot>
                               
                            </table>
                        </div>
                        <div class="dashboard-detail-outer p-4 paymentdiv" >
						 <form 
                            role="form" 
                            action="{{ route('frontend.paymentpost') }}" 
                            method="post" 
                            class="require-validation"
                            data-cc-on-file="false"
                            data-stripe-publishable-key="{{ env('STRIPE_KEY') }}"
                            id="payment-form">
                        @csrf
                        @if($paymentGt == 1)
                        <div class="form-group mb-4">
                       
									<div class="row">
										<div class="col-md-6 required">
											<label class="form-label">Name on Card</label>
											<input class='form-control'  size='4' value="Test" type='text'>
										</div>
										<div class="col-md-6  required">
											<label class="form-label">Card Number</label>
											<input autocomplete='off' class='form-control card-number' value="4242424242424242"  size='20'  type='text'>
										</div>
									</div>
								</div>
                                <div class="form-group mb-4">
									<div class="row">
										<div class="col-md-3  required">
											<label class="form-label">CVC</label>
											<input autocomplete='off'  class='form-control card-cvc'  value="123" placeholder='ex. 311' size='4'  type='text'>
										</div>
										<div class="col-md-4   required">
											<label class="form-label">Expiration Month</label>
											<input   class='form-control card-expiry-month' value="12" placeholder='MM' size='2'  type='text'>
										</div>
                                        <div class="col-md-5    required">
											<label class="form-label">Expiration Year</label>
                                            <input  class='form-control card-expiry-year' value="2024"  placeholder='YYYY' size='4'  type='text'>
										</div>
									</div>
								</div>
                      
  
						
						<div class='form-row row'>
                            <div class='col-md-12 error form-group hide'>
                                <div class='alert-danger alert'>Please correct the errors and try
                                    again.</div>
                            </div>
                        </div>
                       @endif
  
                        <div class="row">
                             <div class="payment-action-btns d-flex flex-wrap justify-content-center mt-md-5 mt-4">
                        <button class="btn btn-fade" id="CancelBtn">Cancel</button>
                        <button type="submit" class="btn btn-primary ms-md-5 ms-3 buy_now" id="rzp-button1" value="Pay">Confirm</button>
                      
                    </div>
                        </div>
                        <input type="hidden"  id="cid"  name="cid" value="{{$course->uuid}}" />
                    </form>
                    </div>
                    </div>
                   
                </div>
            </div>
        </div>
    </section>
	@endsection
@section('scripts')
    <!-- Order Summary Ends-->
    @if($paymentGt == 1)
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
  
<script type="text/javascript">
$(function() {
   
    var $form         = $(".require-validation");
   
    $('form.require-validation').bind('submit', function(e) {
        var $form         = $(".require-validation"),
        inputSelector = ['input[type=email]', 'input[type=password]',
                         'input[type=text]', 'input[type=file]',
                         'textarea'].join(', '),
        $inputs       = $form.find('.required').find(inputSelector),
        $errorMessage = $form.find('div.error'),
        valid         = true;
        $errorMessage.addClass('hide');
  
        $('.has-error').removeClass('has-error');
        $inputs.each(function(i, el) {
          var $input = $(el);
          if ($input.val() === '') {
            $input.parent().addClass('has-error');
            $errorMessage.removeClass('hide');
            e.preventDefault();
          }
        });
   
        if (!$form.data('cc-on-file')) {
          e.preventDefault();
          Stripe.setPublishableKey($form.data('stripe-publishable-key'));
          Stripe.createToken({
            number: $('.card-number').val(),
            cvc: $('.card-cvc').val(),
            exp_month: $('.card-expiry-month').val(),
            exp_year: $('.card-expiry-year').val()
          }, stripeResponseHandler);
        }
  
  });
  
  function stripeResponseHandler(status, response) {
        if (response.error) {
            $('.error')
                .removeClass('hide')
                .find('.alert')
                .text(response.error.message);
        } else {
            /* token contains id, last4, and card type */
            var token = response['id'];
               
            $form.find('input[type=text]').empty();
            $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
            $form.get(0).submit();
        }
    }
});
    </script>

    @endif
    <script>
        $(function() {
   $('#CancelBtn').on('click', function () {
            window.location.href = "{{ route('frontend.paymentFaild') }}";

        });

        $("#coupon_code_button").click(function() { 
            $("#coupon_code_button").prop('disabled',true);
            var code =$("#coupon_code").val();		
            var cid =$("#cid").val();
		 
		 $.ajax({
			 type:'POST',
			 url:"{{route('frontend.applyCoupon')}}",
			 data:{  
			 "code": code,
             "cid": cid,
             "_token": "{{ csrf_token() }}",
		 },
		 dataType:"json",
			 success:function(data){
				 if(data.status==200)
				 {
					toastr['success'](data.message)
                toastr.options = {
                    "closeButton": true,
                    "debug": true,
                    "newestOnTop": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": true,
                    "showDuration": "200",
                    "hideDuration": "2000",
                    "timeOut": "6000",
                    "extendedTimeOut": "2000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                 }
                         $('#coupon_apply_box').show();
                         $('#coupon_input_box').hide();
						$("#coupon_code_button").prop('disabled',false);
                        $("#total_price").html(data.data.newPrice);
                        $("#applied_code").html(data.data.code);

                        location.reload();
				 }
				 else
				 {
					
					toastr['error'](data.message)
                toastr.options = {
                "closeButton": true,
                "debug": true,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": true,
                "showDuration": "200",
                "hideDuration": "2000",
                "timeOut": "6000",
                "extendedTimeOut": "2000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            		} 

					$("#coupon_code_button").prop('disabled',false);
                    
				 }
				 
				 
			 }
		 });
	
	
	 });

$("#coupon_code_remove_button").click(function() { 
            var code =$("#applied_code").html();		
            var cid =$("#cid").val();
		 
		 $.ajax({
			 type:'POST',
			 url:"{{route('frontend.removeCoupon')}}",
			 data:{  
			 "code": code,
             "cid": cid,
             "_token": "{{ csrf_token() }}",
		 },
		 dataType:"json",
			 success:function(data){
				 if(data.status==200)
				 {
					toastr['success'](data.message)
                toastr.options = {
                    "closeButton": true,
                    "debug": true,
                    "newestOnTop": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": true,
                    "showDuration": "200",
                    "hideDuration": "2000",
                    "timeOut": "6000",
                    "extendedTimeOut": "2000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                 }
                         $('#coupon_apply_box').hide();
                         $('#coupon_input_box').show();
                        $("#total_price").html(data.data.newPrice);
                        $("#coupon_code").val('');

                        location.reload();
				 }
				 else
				 {
					
					toastr['error'](data.message)
                toastr.options = {
                "closeButton": true,
                "debug": true,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": true,
                "showDuration": "200",
                "hideDuration": "2000",
                "timeOut": "6000",
                "extendedTimeOut": "2000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            		} 

                    
				 }
				 
				 
			 }
		 });
	
	
	 });
});
</script>
@endsection
