@extends('frontend.layouts.app')
@section('styles')
<link rel="stylesheet" href="https://cdn.plyr.io/3.6.2/plyr.css" />
<style>
    #video_player_box{
        --plyr-video-controls-background: linear-gradient(rgba(255, 255, 255, 0.8),rgba(220, 220, 220, 0.8)) ;
        --plyr-video-control-color: #333333; 
        height: 350px;
    }
    .plyr--video .plyr__controls{
        padding-top: 15px !important;
    }
</style>
@endsection

@section('scripts')


<script src="https://cdn.plyr.io/3.6.2/plyr.js"></script>
<script>
    const player = new Plyr('#video_player_box',{
        settings: ['captions', 'quality', 'speed', 'loop'],        
      });
      
      player.on('ended', event => {
        player.restart();
      });
</script>
@endsection

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
							<span class="mx-2">></span>Details
						</li>
					</ul>
				</div>
			</div>
		</div>
	</section>

	<!-- Breadcrumbs Ends-->

	
	<section class="product-detail-main py-5">
		<div class="container">
			<div class="row gx-lg-5">
				<div class="col-lg-8">
					<h2 class="section-heading">{{$course->name}}</h2>
					@php
						if (strpos($course->demo_video_url,'vimeo.com') !== false) {
						$toutube = 0;
						} else {
							$toutube = 1;
						}
						@endphp

					<div class="course-product-block mt-4 lesson-video" @if ($toutube=='1')id="video_player_box" @endif>
					<iframe class="bg-dark" src="{{$course->demo_video_url}}?byline=false"  id="videoPlayer" width="100%" height="350"  frameborder="0" allow="autoplay; fullscreen"  allowfullscreen></iframe>
					
					</div>
					<div class="product-detail-block mt-rem-10" >
						<div class="product-details-action-btn-block">
						<h2 class="section-heading">Course content</h2>
						<div class="accordion mt-4" id="accordionExample">
						@foreach($subjects as $key1 => $subject)
							
							<div class="accordion-item">
								<h2 class="accordion-header" id="heading{{$key1}}">
									<button class="accordion-button" type="button" data-bs-toggle="collapse"
										data-bs-target="#collapse{{$key1}}" aria-expanded="true" aria-controls="collapseOne">
										{{$subject->subject_name}}
									</button>
								</h2>
								<div id="collapse{{$key1}}" class="accordion-collapse collapse "
									aria-labelledby="headingOne" data-bs-parent="#accordionExample">
									<div class="accordion-body">
										<ul class="mb-0 my-courses-cont-link">
										@foreach($subject->topics as $key1 => $topic)
											<li>
												<a  class="link">{{$topic->topic_name}}</a>
											</li>
											@endforeach
										</ul>
									</div>
								</div>
							</div>
						
						@endforeach	
						</div>
					</div>
					
					</div>
					<div class="product-detail-block mt-5">
						<div class="custom-tabbing">
							<ul class="nav nav-tabs" id="myTab" role="tablist">
								<li class="nav-item " role="presentation">
									<a class="nav-link active" id="home-tab" data-bs-toggle="tab"
										data-bs-target="#home" href="#home" role="tab" aria-controls="home"
										aria-selected="true">Descriptions</a>
								</li>
							
							
							</ul>
							<div class="tab-content" id="myTabContent">
							<div class="tab-pane show  active" id="home" role="tabpanel"
									aria-labelledby="home-tab">
									<div class="tabbing-block">
										<h3>Course Description</h3>
										<p class="description">{!!$course->description!!}</p>
									</div>
									
								</div>
							
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="product-details-action-btn-block">
						<ul class="p-0 list-unstyled">
						<li>
								<span>
									<img src="{{asset('images/p-icon-course.svg')}}" style="height: 35px;" alt="Icon" /> Course Type : 
								</span>
								<span>{{$course->school->school_name}}</span>
							</li>
							<li>
								<span>
									<img src="{{asset('images/p-icon-subject.svg')}}" style="height: 35px;" alt="Icon" /> Course : 
								</span>
								<span>{{$course->name}}</span>
							</li>
							
							<li>
								<span>
									<img src="{{asset('images/p-icon3.svg')}}" style="height: 35px;" alt="Icon" /> Price : 
								</span>
								<span>{!!Config::get('constants.currency')!!}@if($course->course_price==0) Free @else {{$course->course_price}} @endif</span>
							</li>
						</ul>
						@if(isset(Auth::user()->id))
							<form action="{{ route('frontend.payment') }}" method="get">
						{{ csrf_field() }}
						<input type="hidden"  name="cid" value="{{$course->uuid}}" />
						
						<button type="submit" class="buy_now-btn btn btn-primary w-100 mt-4">Buy Now</button>
						</form>
						@else
							<a href="{{route('login')}}"><button class="buy_now-btn btn btn-primary w-100 mt-4">Buy Now</button></a>
						@endif
				</div>
				</div>
			</div>
		</div>
	</section>


@endsection
