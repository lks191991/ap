@extends('frontend.layouts.app')

@section('content')

    
	<!-- Banner -->

	<section class="hero-banner d-flex flex-wrap align-items-center position-relative">
		<div class="container position-relative">
			<div class="row">
				<div class="col-lg-8 mx-auto text-center">
					
					
					<div class="banner-form">
							<form action="{{ route('course-search') }}" method="get">
							{{ csrf_field() }}
							<div class="form-group row">
								<div class="col-md-3 px-md-0">
									<select class="w-100" required name="search_courses" style="border:3px solid #0dcaf0;border-right: 0px;">
										<option value="">Select Courses</option>
										 @foreach($allCoursesList as $acl)
										  <option value="{{$acl->id}}">
										   {{$acl->name}}
										  </option>
										@endforeach
									</select>
								</div>
								<div class="col-md-9 px-md-0 position-relative">
									<input type="text" class="w-100" id="search" style="border:3px solid #0dcaf0" name="search_text" placeholder="What do you want to learn?" />
									<button type="submit"><i class="fas fa-search"></i></button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Banner Ends-->

	<!-- Top courses -->

	<section class="top-courses section-padding">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="section-title text-center">
						<h2 class="section-heading with-bottom-line">Our Top Courses</h2>
						<p class="description">Browse The Collection of Top Courses</p>
					</div>
				</div>
			</div>
			<div class="row">
			@foreach($topCourses as $topCourse)
				<div class="col-lg-3 col-md-6">
					<div class="product-block">
						<div class="product-thumbnail">
							<img src="{{ asset($topCourse->banner_image)}}" alt="product" />
						</div>
						<div class="product-content">
							<h3>{{$topCourse->name}}</h3>
							<ul class="p-0 mt-0 list-unstyled d-flex rating-stars">
								<li>
									<i class="fas fa-star"></i>
								</li>
								<li>
									<i class="fas fa-star"></i>
								</li>
								<li>
									<i class="fas fa-star"></i>
								</li>
								<li>
									<i class="fas fa-star"></i>
								</li>
								<li>
									<i class="far fa-star"></i>
								</li>
							</ul>
							<!--<div class="price">
								<span class="old-price">$1500</span>
								<span class="regular-price"></span>
							</div>-->
							<div class="view-product-detail mt-3 mb-2">
								<a href="{{route('course-list',['CourseId'=>$topCourse->id])}}" class="btn btn-primary w-100">View</a>
							</div>
						</div>
					</div>
				</div>
				@endforeach
				
			</div>
		</div>
	</section>

	<!-- Top courses Ends-->

	<!-- Key benifts -->

	<section class="key-benifits section-padding bg-light">
		<div class="container">
			<div class="row">
				<div class="col-md-4">
					<div class="key-benifts-block">
						<div class="key-benifts-icon">
							<i class="fas fa-bullseye"></i>
						</div>
						<div class="key-benefits-cnt">
							<h4>Many Online courses</h4>
							<p class="mb-0">Explore a variety of fresh topics</p>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="key-benifts-block">
						<div class="key-benifts-icon">
							<i class="fas fa-check"></i>
						</div>
						<div class="key-benefits-cnt">
							<h4>Expert instruction</h4>
							<p class="mb-0">Find the right course for you</p>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="key-benifts-block">
						<div class="key-benifts-icon">
							<i class="fas fa-clock"></i>
						</div>
						<div class="key-benefits-cnt">
							<h4>Lifetime access</h4>
							<p class="mb-0">Learn on your schedule</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Key benifts Ends -->

	<!-- Latest courses -->

	<section class="top-courses section-padding">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="section-title text-center">
						<h2 class="section-heading with-bottom-line">Our Latest Courses</h2>
						<p class="description">Browse The Collection of Latest Courses</p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="owl-carousel owl-theme theme-owl-style">
				@foreach($latestCourses as $topCourse)
					<div class="item">
						<div class="product-block">
							<div class="product-thumbnail">
								<img src="{{ asset($topCourse->banner_image)}}" alt="product" />
							</div>
							<div class="product-content">
								<h3>{{$topCourse->name}}</h3>
								<ul class="p-0 mt-0 list-unstyled d-flex rating-stars">
									<li>
										<i class="fas fa-star"></i>
									</li>
									<li>
										<i class="fas fa-star"></i>
									</li>
									<li>
										<i class="fas fa-star"></i>
									</li>
									<li>
										<i class="fas fa-star"></i>
									</li>
									<li>
										<i class="far fa-star"></i>
									</li>
								</ul>
								<!--<div class="price">
									<span class="old-price">$1500</span>
									<span class="regular-price">$999</span>
								</div>-->
								<div class="view-product-detail mt-3 mb-2">
									<a href="{{route('course-list',['CourseId'=>$topCourse->id])}}" class="btn btn-primary w-100">View</a>
								</div>
							</div>
						</div>
					</div>
					@endforeach
				</div>
			</div>
		</div>
	</section>

	<!-- Latest courses Ends-->

	<!-- Subscribe newsletter -->

	<section class="subscribe-newsletter section-padding bg-secondary">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-5">
					<div class="section-title text-white">
						<h2 class="section-heading mb-3 text-white">Subscribe to our newsletter</h2>
						
					</div>
				</div>
				<div class="col-lg-7">
					<div class="custom-form-style absoulte-btn">
					<form action="{{route('frontend.newsletterSave')}}" method="POST" id="registerForm" >@csrf
							<div class="form-group position-relative">
								<input type="text" placeholder="Enter your Email" class="w-100 form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" name="email" value="{{ old('email') }}" data-validation="required length email" placeholder="Enter your email" data-validation-length="2-255" >
						@if($errors->has('email'))
							<div class="invalid-feedback">{!! $errors->first('email') !!}</div>
						@endif
								<button type="submit" class="btn btn-primary"><i
										class="far fa-paper-plane"></i></button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Subscribe newsletter End-->

@endsection
