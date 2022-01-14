@extends('frontend.layouts.app')

@section('content')

<!-- Breadcrumbs -->

	<section class="breadcrumbs bg-light py-3">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<ul class="m-0 p-0 list-unstyled d-flex flex-wrap">
						<li>
							<a href="#">Home</a>
						</li>
						<li>
							<span class="mx-2">></span>Login
						</li>
					</ul>
				</div>
			</div>
		</div>
	</section>

	<!-- Breadcrumbs Ends-->
	
<!-- Login Main -->

	<section class="login-signup-main main-login section-padding">
		<div class="container">
		<div class="row mobilenumberdiv">
				<div class="col-lg-6 mx-auto">
					<div class="login-signup-form-block">
						<h1 class="text-uppercase text-center mb-5">Login</h1>
						<form  method="post" autocomplete="off">
		{{ csrf_field() }}
		<div class="form-group mb-4">
								@includeif('frontend.message')
							</div>
							
							<div class="form-group mb-4">
								<label class="form-label" style="width: 100%;"><span style="float: left;">Mobile</span> <a href="{{route('password.request')}}" style="float: right;"  class="text-right link text-decoration-none">Forgot Password?</a></label>
									<input type="text"  name="mobile"  id="mobile" autocomplete="off" class="form-control {{ $errors->has('mobile') ? 'is-invalid' : '' }}" value="{{ old('mobile') }}" placeholder="{{ __('Mobile') }}">
									@if ($errors->has('mobile'))
									<span class="d-block link-danger errorMsg"><small>{{ $errors->first('mobile') }}</small></span>
									@endif

							</div>
							
							<div class="from-group mb-4" >
								
								<div class="forgot-password" >
									
										<a href="{{route('login')}}" style="float: right;" class="pull-right link text-decoration-underline">LOGIN WITH PASSWORD</a>
								</div>
							</div>
							<div class="from-group mb-4">
								<button type="button" class="btn btn-primary" id="sendOtpButtion">GET OTP</button>
							</div>
							<div class="from-group mb-4">

								<div class="didnt-have-account">
									Didn't have an account? <a href="{{route('register')}}" class="link text-decoration-underline">Click
										here to
										create</a>
								</div>
								
							</div>
						</form>


					</div>
				</div>
			</div>

			<div class="row mobilenumberotpdiv" style="display:none">
				<div class="col-lg-6 mx-auto">
					<div class="login-signup-form-block">
						<h1 class="text-uppercase text-center mb-5">Login</h1>
						<form  method="post" autocomplete="off">
						{{ csrf_field() }}
						<div class="form-group mb-4">
								@includeif('frontend.message')
							</div>
							<div class="form-group mb-4">
								<label class="form-label" ><span id="mobileenter"></span> <a href="javascript:void(0)" class="link text-decoration-none" id="mobilechange">Change</a>
									
								</label>
									

							</div>
							<div class="form-group mb-4">
								<label class="form-label">OTP</label>
									<input type="text"  name="otp"  id="otp" autocomplete="off" class="form-control {{ $errors->has('otp') ? 'is-invalid' : '' }}" value="{{ old('otp') }}" placeholder="{{ __('OTP') }}">
									@if ($errors->has('otp'))
									<span class="d-block link-danger errorMsg"><small>{{ $errors->first('otp') }}</small></span>
									@endif

							</div>
							
							<div class="from-group mb-4" >
								
								<div class="forgot-password" >
									
										<a href="{{route('login')}}" style="float: right;" class="pull-right link text-decoration-underline">LOGIN WITH PASSWORD</a>
								</div>
							</div>
							<div class="from-group mb-4">
								<button type="button" class="btn btn-primary" id="OtpSubButtion">Submit</button>
							</div>
							
						</form>


					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Login Main Ends-->

</section>



@endsection

@section('scripts')
<script>	
$(document).ready(function(){ 
	$("#mobilechange").click(function() {
		$('.mobilenumberdiv').show();
		$('.mobilenumberotpdiv').hide();
	});
	 $("#sendOtpButtion").click(function() { 
		 $(this).attr('disabled',true);
	 var mobile=$("#mobile").val();
	 var phoneno = /^\d{10}$/;		
	 if(mobile.match(phoneno))
	 {
		 
		 $.ajax({
			 type:'POST',
			 url:"{{route('contactLoginOtp')}}",
			 data:{  
			 "mobile": mobile,
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
						$('.mobilenumberdiv').hide();
						$('.mobilenumberotpdiv').show();
						$("#mobileenter").text(mobile);
						$("#sendOtpButtion").prop('disabled',false);
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

					$("#sendOtpButtion").prop('disabled',false);
				 }
				 
				 
			 }
		 });
	 }
	 else{
		toastr['error']("Please Enter Corect Contact Number")
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

			$("#sendOtpButtion").prop('disabled',false);
	 }
	
	 });

	 $("#OtpSubButtion").click(function() { 
		$(this).attr('disabled',true);
	 var otp=$("#otp").val();
	 if(otp!='')
	 {
		 
		 $.ajax({
			 type:'POST',
			 url:"{{route('contactLoginOtpVerify')}}",
			 data:{  
			 "otp": otp,
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
				$("#OtpSubButtion").prop('disabled',false);
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
			$("#OtpSubButtion").prop('disabled',false);
				 }
				 
				 
			 }
		 });
	 }
	 else{
		toastr['error']("Please Enter Corect OTP")
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

			$("#OtpSubButtion").prop('disabled',false);
	 }
	
	 });

	});
	</script>
@endsection