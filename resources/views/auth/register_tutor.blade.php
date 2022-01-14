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
							<span class="mx-2">></span>Sign Up
						</li>
					</ul>
				</div>
			</div>
		</div>
	</section>
<section class="login-signup-main main-login section-padding">
		<div class="container">
			<div class="row">
				<div class="col-lg-9 mx-auto">
					<div class="login-signup-form-block">
						<h1 class="text-uppercase text-center mb-5">Tutor Sign Up</h1>
						<form action="{{ route('register') }}" id="registerForm" method="post">
			{{ csrf_field() }}
					<input 	 type="hidden" value="tutor" name="rejister_as" />
							<div class="row">
								<div class="col-lg-6">
							<div class="form-group mb-4">
								<label class="form-label">First name</label>
								<input type="text" class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}" value="{{old('first_name')}}" id="first_name" autocomplete="off" name="first_name" placeholder="First name">
								@if ($errors->has('first_name'))
									<span class="d-block link-danger errorMsg"><small>{{ $errors->first('first_name') }}</small></span>
									@endif
							</div>
							</div>
							<div class="col-lg-6">
							<div class="form-group mb-4">
								<label class="form-label">Last name</label>
								 <input type="text" class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}" value="{{old('last_name')}}" id="last_name" autocomplete="off" name="last_name" placeholder="Last name">
								 @if ($errors->has('last_name'))
									<span class="d-block link-danger errorMsg"><small>{{ $errors->first('last_name') }}</small></span>
									@endif
							</div>
							</div>
							<div class="col-lg-6">
							<div class="form-group mb-4">
								<label class="form-label">Email</label>
								<input type="email" class="form-control  {{ $errors->has('email') ? 'is-invalid' : '' }}" id="email" value="{{old('email')}}" name="email" autocomplete="off"  placeholder="Email">
								 @if ($errors->has('email'))
									<span class="d-block link-danger errorMsg"><small>{{ $errors->first('email') }}</small></span>
									@endif
							</div>
							</div>
							<div class="col-lg-6">
							<div class="form-group mb-4">
								<label class="form-label">Student id</label>
								<input type="text" class="form-control  {{ $errors->has('student_id') ? 'is-invalid' : '' }}" id="student_id" value="{{old('student_id')}}" autocomplete="off" name="student_id" autocomplete="off" aria-describedby="student_idHelp" placeholder="Student id">
								 @if ($errors->has('student_id'))
									<span class="d-block link-danger errorMsg"><small>{{ $errors->first('student_id') }}</small></span>
									@endif
							</div>
							</div>
							<div class="col-lg-6">
							<div class="form-group mb-4">
								<label class="form-label">Student Type</label>
								<select class="form-select" id="student_type" name="student_type">
									<option value="General">General</option>
									<option value="Vocational">Vocational</option>
									</select>
								 @if ($errors->has('student_type'))
									<span class="d-block link-danger errorMsg"><small>{{ $errors->first('student_type') }}</small></span>
									@endif
							</div>
							</div>
							<div class="col-lg-6">
							<div class="form-group mb-4">
								<label class="form-label">Branch</label>
								 <input type="text" class="form-control {{ $errors->has('branch') ? 'is-invalid' : '' }}" value="{{old('branch')}}" id="branch" autocomplete="off" name="branch" placeholder="Branch">
								 @if ($errors->has('branch'))
									<span class="d-block link-danger errorMsg"><small>{{ $errors->first('branch') }}</small></span>
									@endif
							</div>
							</div>
							<div class="col-lg-6">
							<div class="form-group mb-4">
								<label class="form-label">Mobile</label>
								 <input type="text" class="form-control {{ $errors->has('mobile') ? 'is-invalid' : '' }}" value="{{old('mobile')}}" id="mobile" autocomplete="off" name="mobile" placeholder="Mobile">
								 @if ($errors->has('mobile'))
									<span class="d-block link-danger errorMsg"><small>{{ $errors->first('mobile') }}</small></span>
									@endif
							</div>
							</div>
							<div class="col-lg-6">
							<div class="form-group mb-4">
								<label class="form-label">Father Name</label>
								 <input type="text" class="form-control {{ $errors->has('father_name') ? 'is-invalid' : '' }}" value="{{old('father_name')}}" id="father_name" autocomplete="off" name="father_name" placeholder="Father Name">
								 @if ($errors->has('father_name'))
									<span class="d-block link-danger errorMsg"><small>{{ $errors->first('father_name') }}</small></span>
									@endif
							</div>
							</div>
							<div class="col-lg-6">
							<div class="form-group mb-4">
								<label class="form-label">Password</label>
								  <input type="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" id="password" name="password" placeholder="Password">
								   @if ($errors->has('password'))
									<span class="d-block link-danger errorMsg"><small>{{ $errors->first('password') }}</small></span>
									@endif
							</div>
							</div>
							<div class="col-lg-6">
							<div class="form-group mb-4">
								<label class="form-label">Confirm password</label>
									<input type="password" name="password_confirmation" id="password_confirmation" class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}" placeholder="{{ __('Confirm password') }}" data-validation="required length custom" data-validation-length="min6" >
									  @if ($errors->has('password_confirmation'))
									<span class="d-block link-danger errorMsg"><small>{{ $errors->first('password_confirmation') }}</small></span>
									@endif
							</div>
							</div>
						
							<div class="col-lg-6">
							<div class="form-group mb-4">
								<label class="form-label">State</label>
							  <select class="form-select" id="state_name" name="state_name" >
							  <option value="" selected="" disabled="" class="d-none">Select State</option>
							  @foreach($states as $id => $state)
										<option value="{{$id}}" >{{$state}}</option>
                                @endforeach
							</select>
							@if ($errors->has('state_name'))
									<span class="d-block link-danger errorMsg"><small>{{ $errors->first('state_name') }}</small></span>
									@endif
							</div>
							</div>
							<div class="col-lg-6">
							<div class="form-group mb-4">
								<label class="form-label">Zone</label>
									<select name="zone_name" id="zones" class="form-select" >
									<option value="">Select Zone</option>
									</select>
									@if ($errors->has('state_name'))
									<span class="d-block link-danger errorMsg"><small>{{ $errors->first('state_name') }}</small></span>
									@endif
									</div>
							</div>
							<div class="col-lg-6">
							<div class="form-group mb-4">
								<label class="form-label">District</label>
								<select name="district_name" id="districts" class="custom-select" >
								<option value="">Select District</option>
								</select>
								@if ($errors->has('district_name'))
									<span class="d-block link-danger errorMsg"><small>{{ $errors->first('district_name') }}</small></span>
									@endif
								</div>
							</div>
							<div class="col-lg-6">
							<div class="form-group mb-4">
								<label class="form-label">City</label>
								<select name="city_name" id="cities" class="custom-select" >
						<option value="">Select City</option>
					</select>
					@if ($errors->has('city_name'))
									<span class="d-block link-danger errorMsg"><small>{{ $errors->first('city_name') }}</small></span>
									@endif
					</div>
							</div>
							<div class="col-lg-6">
							<div class="form-group mb-4">
								<label class="form-label">College</label>
								<select name="college_name" id="colleges" class="custom-select" >
						<option value="">Select College</option>
					</select>
					@if ($errors->has('college_name'))
									<span class="d-block link-danger errorMsg"><small>{{ $errors->first('college_name') }}</small></span>
									@endif
					</div>
							</div>
							<div class="col-lg-6">
							<div class="form-group mb-4">
								<label class="form-label">Profile Photo</label>
								<input type="file" class="form-control {{ $errors->has('photo') ? 'is-invalid' : '' }}" value="{{old('photo')}}" id="photo" autocomplete="off" name="photo" >
								<small class="form-text mb-4">
							.jpg .png .bmp  |  Size max >= 2mb<br>
							@if ($errors->has('photo'))
									<span class="d-block link-danger errorMsg"><small>{{ $errors->first('photo') }}</small></span>
									@endif
						</small>
					</div>
							</div>
							</div>
							<div class="form-group mb-4">
								<button type="submit" class="btn btn-primary">Sign Up</button>
							</div>
							<div class="from-group mb-4">
								<div class="didnt-have-account">
									Already have an account? <a href="{{route('login')}}" class="link text-decoration-underline">Login
										here</a>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Breadcrumbs Ends-->

@stop