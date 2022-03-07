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
							<span class="mx-2">></span>Profile
						</li>
					</ul>
				</div>
			</div>
		</div>
	</section>

	<!-- Breadcrumbs Ends-->
<section class="user-dashboard section-padding">
		<div class="container">
			<div class="row gx-lg-5">
				@include('frontend.includes.side')
				<div class="col-lg-8">
					<div class="dashboard-main-content mt-lg-0 mt-5">
						<div class="section-title">
							<h1 class="section-heading with-bottom-line text-center">My Profile</h1>
						</div>
						<div class="dashboard-detail-outer pt-4">
							<form  action="{{route('frontend.updateProfileStudent')}}" id="updateForm" class="" method="post" enctype="multipart/form-data">
                            @csrf
								<div class="form-group mb-4">
									<div class="row">
										<div class="col-md-6">
											<label class="form-label">First Name</label>
											<input type="text" class="form-control" name="first_name" id="first_name" value="{{ $student->userData->first_name }}"  placeholder="First name" data-validation-length="2-255" >
											@if ($errors->has('first_name'))
											<span class="d-block link-danger errorMsg"><small>{{ $errors->first('first_name') }}</small></span>
											@endif
										</div>
										<div class="col-md-6">
											<label class="form-label">Last Name</label>
											<input type="text" class="form-control" name="last_name" id="last_name" value="{{ $student->userData->last_name }}"  placeholder="First name" data-validation-length="2-255" >
											 @if ($errors->has('last_name'))
									<span class="d-block link-danger errorMsg"><small>{{ $errors->first('last_name') }}</small></span>
									@endif
										</div>
									</div>
								</div>
								<div class="from-group mb-4">
								<div class="row">
								<div class="col-md-6">
											<label class="form-label">Mobile</label>
											<input type="text" class="form-control" disabled  value="{{ $student->userData->mobile }}"  placeholder="First name" data-validation-length="2-255" >
											
										</div>
										<div class="col-md-6">
									<label class="form-label">Email</label>
									<input type="email" class="form-control" disabled  value="{{ $student->userData->email }}"  placeholder="Email" data-validation-length="2-255" >
									
								</div>
								</div>
								</div>
								<div class="from-group mb-4">
									<div class="row">
									<div class="col-md-6">
											<label class="form-label">Course Type</label>
								<select name="student_type" id="student_type" class="form-control" required>
													<option value="General" @if('General' == $student->userData->student_type ) selected @endif >General</option>
													<option value="Vocational" @if('Vocational' == $student->userData->student_type ) selected @endif >Vocational</option>
												</select>
												@if ($errors->has('student_type'))
									<span class="d-block link-danger errorMsg"><small>{{ $errors->first('student_type') }}</small></span>
									@endif
										</div>
										
										<div class="col-md-6">
											<label class="form-label">DOB</label>
											
											<input type="text" class="form-control" name="dob" id="dob" readonly value="{{ $student->userData->dob }}"  placeholder="DOB" data-validation-length="2-255" >
											
										</div>
									</div>
								</div>
								
									</div>
										<div class="row">
									
										<div class="col-md-6">
											<label class="form-label">Profile Photo</label>
										<input type="file" id="photo" class="form-control"  name="photo">
										<small class="d-block">
										.jpg .png .bmp  |  Size max >= 2mb<br>
										</small>
										@if ($errors->has('photo'))
									<span class="d-block link-danger errorMsg"><small>{{ $errors->first('photo') }}</small></span>
									@endif
											</div>
										
									</div>
									
									
								<div class="from-group mb-4 " >
									<div class="d-flex justify-content-between flex-wrap" style="float:right;">
										<button type="submit" class="btn btn-primary">Save</button>
									</div>
									</div>
								</div>
									
								
								</div>
								
								
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

@endsection