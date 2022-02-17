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
				<div class="col-lg-8 mx-auto">
					<div class="login-signup-form-block">
						<h1 class="text-uppercase text-center mb-5">Sign Up</h1>
						<form action="{{ route('register') }}" id="registerForm" method="post">
			{{ csrf_field() }}
							<div class="form-group mb-4">
							<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" checked @if(old('register_as')=='student') checked @endif value="student" name="register_as" id="signupStudent"
										 />
									<label class="form-check-label" for="signupStudent">Signup As Student</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio"  @if(old('register_as')=='tutor') checked @endif value="tutor" name="register_as" id="signupTutor"
										 />
									<label class="form-check-label" for="signupTutor">Signup As Tutor</label>
								</div>
								
								@if ($errors->has('register_as'))
									<span class="d-block link-danger errorMsg"><small>{!! $errors->first('register_as') !!}</small></span>
									@endif
								
							</div>
							<div class="row">
								<div class="col-lg-6">
							<div class="form-group mb-4">
								<label class="form-label">First name</label>
								<input type="text" class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}" value="{{old('first_name')}}" autocomplete="off" id="first_name" name="first_name" placeholder="First name">
								@if ($errors->has('first_name'))
									<span class="d-block link-danger errorMsg"><small>{!! $errors->first('first_name') !!}</small></span>
									@endif
							</div>
							</div>
							<div class="col-lg-6">
							<div class="form-group mb-4">
								<label class="form-label">Last name</label>
								 <input type="text" class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}" value="{{old('last_name')}}" autocomplete="off" id="last_name" name="last_name" placeholder="Last name">
								 @if ($errors->has('last_name'))
									<span class="d-block link-danger errorMsg"><small>{!! $errors->first('last_name') !!}</small></span>
									@endif
							</div>
							</div>
							<div class="col-lg-6">
							<div class="form-group mb-4">
								<label class="form-label">Email</label>
								<input type="email" class="form-control  {{ $errors->has('email') ? 'is-invalid' : '' }}" id="email" value="{{old('email')}}" autocomplete="off" name="email" aria-describedby="emailHelp" placeholder="Email">
								 @if ($errors->has('email'))
									<span class="d-block link-danger errorMsg"><small>{!! $errors->first('email') !!}</small></span>
									@endif
							</div>
							</div>
							<div class="col-lg-6">
							<div class="form-group mb-4">
								<label class="form-label">Contact</label>
								 <input type="text" class="form-control {{ $errors->has('mobile') ? 'is-invalid' : '' }}" value="{{old('mobile')}}" id="mobile" autocomplete="off" name="mobile" placeholder="Contact">
								 @if ($errors->has('mobile'))
									<span class="d-block link-danger errorMsg"><small>{!! $errors->first('mobile') !!}</small></span>
									@endif
							</div>
							</div>
							
							<div class="col-lg-6">
							<div class="form-group mb-4">
								<label class="form-label">Password</label>
								  <input type="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" id="password" name="password" autocomplete="off" placeholder="Password">
								   @if ($errors->has('password'))
									<span class="d-block link-danger errorMsg"><small>{!! $errors->first('password') !!}</small></span>
									@endif
							</div>
							</div>
							<div class="col-lg-6">
							<div class="form-group mb-4">
								<label class="form-label">Confirm password</label>
									<input type="password" name="password_confirmation" id="password_confirmation" class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}" autocomplete="off" placeholder="{{ __('Confirm password') }}" data-validation="required length custom" data-validation-length="min6" >
									  @if ($errors->has('password_confirmation'))
									<span class="d-block link-danger errorMsg"><small>{!! $errors->first('password_confirmation') !!}</small></span>
									@endif
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
