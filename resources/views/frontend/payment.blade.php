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
                                        <th scope="col">School</th>
                                        <th scope="col">Course</th>
                                        <th scope="col">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                    <td>{{$subject->subject_class->class_name}}</td>
                                        <td>{{$subject->subject_name}}</td>
                                        <td><b><i class="fas fa-rupee-sign"></i>{{$subject->subject_price}}</b></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2">Apply Coupon Code</th>
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
                                        <th colspan="2">Total</th>
                                        @if(session('discount')==0)
                                        <td><b><i class="fas fa-rupee-sign"></i><span id="total_price">{{$subject->subject_price}}</span></td>
                                        @else
                                        <td><b><i class="fas fa-rupee-sign"></i><span id="total_price">{{session('newPrice')}}</span></td>
                                        @endif
                                    </tr>
                                </tfoot>
                               
                            </table>
                        </div>

						
                        @csrf
  
                        <input type="hidden"  id="sid" value="{{$subject->uuid}}" />

  
                        <div class="row">
                             <div class="payment-action-btns d-flex flex-wrap justify-content-center mt-md-5 mt-4">
                        <button class="btn btn-fade" id="CancelBtn">Cancel</button>
                        <button type="button" class="btn btn-primary ms-md-5 ms-3 buy_now" id="rzp-button1" value="Pay">Confirm</button>
                    </div>
                        </div>
                          
                    </form>
                    </div>
                   
                </div>
            </div>
        </div>
    </section>
	@endsection
@section('scripts')
    <!-- Order Summary Ends-->
    @if($razorpayShow >0 )
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
   
 <script>
var options = {
    //"key": "rzp_test_8zkjGQQlUyxldh", // Enter the Key ID generated from the Dashboard
    "key": "{{env('RAZORPAY_KEY')}}", // Enter the Key ID generated from the
    "amount": "{{$amt_rpay}}", // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
    "currency": "INR",
    "name": "{{$subject->subject_name}}",
    "description": "",
    "image": "http://sauryagyan.com/images/logo.png",
    "order_id": "{{$paymentid}}", //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
    "handler": function (response){
        $('#modelNotClose').modal("show");
        $('#modelNotClose').modal({
    backdrop: 'static',   // This disable for click outside event
    keyboard: true        // This for keyboard event
})
if (typeof response.razorpay_payment_id == 'undefined' ||  response.razorpay_payment_id < 1) {
	var r_payment_id =0;
	var status_order ="Failed";
	var status_payment ="Failed";
}
else
{
	var r_payment_id =response.razorpay_payment_id;
	var status_order ="Placed";
	var status_payment ="Success";
}
            $.post("{{ route('frontend.paymentpost') }}",
            {
				razorpay_payment_id: r_payment_id,
				"_token": "{{ csrf_token() }}",
				sid: $("#sid").val(),
				status_order: status_order,
				status_payment: status_payment,
				order_no: "",
            },
            function(data, status){
            if(status=='success')
            {
				if(status_payment =="Failed")
				{
					window.location.href = "{{ route('frontend.paymentFaild') }}"; 
				}
				else if(status_payment =="Success")
				{
					window.location.href = "{{ route('frontend.paymentSuccess') }}"; 
				}
                
            }
			else
            {
                window.location.href = "{{ route('frontend.paymentFaild') }}"; 
            }
            });
        
    },
    "prefill": {
        "name": "{{$userData['name']}}",
        "email": "{{$userData['email']}}",
       "contact": "{{$userData['contact']}}"
    },
    "notes": {
        "address": ""
    },
    "theme": {
        "color": "#F37254"
    }
};
var rzp1 = new Razorpay(options);
document.getElementById('rzp-button1').onclick = function(e){
    rzp1.open();
    e.preventDefault();
}
</script>
@else

   
 <script>
    $('#rzp-button1').on('click', function () { 
        var status_order ="Placed";
	var status_payment ="Success";
$.ajax({
			 type:'POST',
			 url:"{{ route('frontend.paymentpost') }}",
			 data:{  
                razorpay_payment_id: "offer",
				"_token": "{{ csrf_token() }}",
				sid: $("#sid").val(),
				status_order: status_order,
				status_payment: status_payment,
				order_no: "",
		 },
		 dataType:"json",
			 success:function(data){
				if(data.status=='success')
                {
                        window.location.href = "{{ route('frontend.paymentSuccess') }}"; 
                }
                else
                {
                    window.location.href = "{{ route('frontend.paymentFaild') }}"; 
                }
				 
				 
			 }
		 });
         });
</script>
@endif

<script>
$('#CancelBtn').on('click', function () {
            window.location.href = "{{ route('frontend.paymentFaild') }}";

        });

        $("#coupon_code_button").click(function() { 
            $("#coupon_code_button").prop('disabled',true);
	 var code =$("#coupon_code").val();		
     var sid =$("#sid").val();
		 
		 $.ajax({
			 type:'POST',
			 url:"{{route('frontend.applyCoupon')}}",
			 data:{  
			 "code": code,
             "sid": sid,
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
            var sid =$("#sid").val();
		 
		 $.ajax({
			 type:'POST',
			 url:"{{route('frontend.removeCoupon')}}",
			 data:{  
			 "code": code,
             "sid": sid,
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

</script>
@endsection