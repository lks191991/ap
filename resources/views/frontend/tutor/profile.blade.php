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
						<form  action="{{route('frontend.updateProfileTutor')}}" id="updateForm" class="" method="post" enctype="multipart/form-data">
                            @csrf
								<div class="form-group mb-4">
									<div class="row">
										<div class="col-md-6">
											<label class="form-label">First Name</label>
											<input type="text" class="form-control" name="first_name" id="first_name" value="{{ $tutor->userData->first_name }}"  placeholder="First name" data-validation-length="2-255" >
											@if ($errors->has('first_name'))
											<span class="d-block link-danger errorMsg"><small>{{ $errors->first('first_name') }}</small></span>
											@endif
										</div>
										<div class="col-md-6">
											<label class="form-label">Last Name</label>
											<input type="text" class="form-control" name="last_name" id="last_name" value="{{ $tutor->userData->last_name }}"  placeholder="First name" data-validation-length="2-255" >
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
											<input type="text" class="form-control" disabled  value="{{ $tutor->userData->mobile }}"  placeholder="First name" data-validation-length="2-255" >
											
										</div>
										<div class="col-md-6">
									<label class="form-label">Email</label>
									<input type="email" class="form-control" disabled  value="{{ $tutor->userData->email }}"  placeholder="Email" data-validation-length="2-255" >
									
								</div>
								</div>
								</div>
								<div class="from-group mb-4">
									<div class="row">
									<div class="col-md-6">
											<label class="form-label">Pricipal Name</label>
											<input type="text" class="form-control" name="pricipal_name" id="pricipal_name" value="{{ $tutor->userData->pricipal_name }}"  placeholder="Pricipal Name" data-validation-length="2-255" >
										@if ($errors->has('pricipal_name'))
									<span class="d-block link-danger errorMsg"><small>{{ $errors->first('pricipal_name') }}</small></span>
									@endif
										</div>
										<div class="col-md-6">
											<label class="form-label">Subject</label>
											<input type="text" class="form-control" name="tutor_subject" id="tutor_subject" value="{{ $tutor->userData->tutor_subject }}"  placeholder="Subject" data-validation-length="2-255" >
										@if ($errors->has('tutor_subject'))
									<span class="d-block link-danger errorMsg"><small>{{ $errors->first('tutor_subject') }}</small></span>
									@endif
										</div>
									
									</div>
								</div>
<div class="from-group mb-4">
									<div class="row">
									<div class="col-md-6">
											<label class="form-label">Gender</label>
											<select name="gender" id="gender" class="form-control" >
											<option value="" >Select</option>
													<option value="Male" @if('Male' == $tutor->userData->gender ) selected @endif >Male</option>
													<option value="Female" @if('Female' == $tutor->userData->gender ) selected @endif >Female</option>
													<option value="Other" @if('Other' == $tutor->userData->gender ) selected @endif >Other</option>
												</select>
												
										</div>
											<div class="col-md-6">
											<label class="form-label">DOB</label>
											
											<input type="text" class="form-control" name="dob" id="dob" readonly value="{{ $tutor->userData->dob }}"  placeholder="DOB"  >
											
										</div>
									
										
									</div>
									</div>
								<div class="from-group mb-4">
									<div class="row">
									
										<div class="col-md-6">
											<label class="form-label">State</label>
											<select name="state_name" id="state_name" class="form-control" required>
											<option value="" selected="" disabled="" class="d-none">Select State</option>
											 @foreach($states as $stateid => $state)
										<option value="{{$stateid}}" @if($stateid ==$tutor->userData->state_id ) selected @endif >{{$state}}</option>
                                @endforeach
											</select>
											@if ($errors->has('state_name'))
									<span class="d-block link-danger errorMsg"><small>{{ $errors->first('state_name') }}</small></span>
									@endif
										</div>
										<div class="col-md-6">
											<label class="form-label">Zone</label>
										<select name="zone_name" id="zones" class="form-control" required>
										<option value="">Select Zone</option>
									  @foreach($zones as $zoneid => $zone)
													<option value="{{$zoneid}}" @if($zoneid == $tutor->userData->zone_id ) selected @endif >{{$zone}}</option>
											@endforeach
										</select>
										@if ($errors->has('zone_name'))
									<span class="d-block link-danger errorMsg"><small>{{ $errors->first('zone_name') }}</small></span>
									@endif
											</div>
										
									</div>
									</div>
									
									<div class="from-group mb-4">
									<div class="row">
									
										<div class="col-md-6">
											<label class="form-label">District</label>
											<select name="district_name" id="districts" class="form-control" required>
											<option value="">Select District</option>
											  @foreach($districts as $districtid => $district)
															<option value="{{$districtid}}" @if($districtid == $tutor->userData->district_id ) selected @endif >{{$district}}</option>
													@endforeach
										</select>
											@if ($errors->has('district_name'))
									<span class="d-block link-danger errorMsg"><small>{{ $errors->first('district_name') }}</small></span>
									@endif
										</div>
										<div class="col-md-6">
											<label class="form-label">City</label>
										<select name="city_name" id="cities" class="form-control" required>
										<option value="">Select City</option>
										 @foreach($cities as $citiesid => $city)
														<option value="{{$citiesid}}" @if($citiesid == $tutor->userData->city_id ) selected @endif >{{$city}}</option>
												@endforeach
									</select>
										@if ($errors->has('city_name'))
									<span class="d-block link-danger errorMsg"><small>{{ $errors->first('city_name') }}</small></span>
									@endif
											</div>
										<div class="col-md-6">
											<label class="form-label">College</label>
									<select name="college_name" id="colleges" class="form-control" required>
										<option value="">Select College</option>
										 @foreach($colleges as $collegeid => $college)
														<option value="{{$collegeid}}" @if($collegeid == $tutor->userData->college_id ) selected @endif >{{$college}}</option>
												@endforeach
									</select>
										@if ($errors->has('college_name'))
									<span class="d-block link-danger errorMsg"><small>{{ $errors->first('college_name') }}</small></span>
									@endif
											</div>
									</div>
									</div>
									<div class="from-group mb-4">
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
									</div>
								</div>
								
								<div class="row">
								<div class="from-group mb-4">
									<div class="d-flex justify-content-between flex-wrap" style="float:right;">
										<button type="submit" class="btn btn-primary">Save</button>
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
