@extends('frontend.layouts.app')
@section('styles')
<link href="{{ asset('css/front/themes/fontawesome-stars.css') }}" rel='stylesheet' type='text/css'>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">

<link rel="stylesheet" href="https://cdn.plyr.io/3.6.2/plyr.css" />
<style>
    #video_player_box{
        --plyr-video-controls-background: linear-gradient(rgba(255, 255, 255, 0.8),rgba(220, 220, 220, 0.8)) ;
        --plyr-video-control-color: #333333; 
        height: 500px;
    }
    .plyr--video .plyr__controls{
        padding-top: 15px !important;
    }
	.favdiv {
    background-color: #d6d6d6;
    padding: 10px;
    margin: 10px;
    width: 97%;
    text-align: right;
}
.btn-custom.fav-btn{margin-left: 15px;}
.btn-custom.fav-btn .icon{padding: 0;color: #868686;}
.btn-custom.fav-btn .icon [class*="fa"]{font-size: 18px;}
.btn-custom.fav-btn .icon.fav-active{color: #094d96;}

</style>

@if($myRateing>0)
<style>
.br-theme-fontawesome-stars .br-widget a:after {
  content: '\f005';
  color: #EDB867;
}
</style>
@endif

@endsection

@section('scripts')
 <script type="text/javascript" src="{{ asset('js/front/jquery.barrating.min.js') }}"></script>

 
<script src="https://cdn.plyr.io/3.6.2/plyr.js"></script>
<script>
    const player = new Plyr('#video_player_box',{
        settings: ['captions', 'quality', 'speed', 'loop'],        
      });
      
      player.on('ended', event => {
        player.restart();
      });
</script>

@if($myRateing>0)
 <script type="text/javascript">
  
   
   $(function() {
 $('.rating').barrating({
  theme: 'fontawesome-stars',
 });
 });
 </script> 
 @endif
@if($myRateing==0)
	<script type="text/javascript">
  
   $(function() {
 $('.rating').barrating({
  theme: 'fontawesome-stars',
  onSelect: function(value, text, event) {
   // Get element id by data-id attribute
   var el = this;
   var el_id = el.$elem.data('id');

   // rating was selected by a user
   if (typeof(event) !== 'undefined') {
 
     var split_id = el_id.split("_");
     var postid = split_id[1]; // postid

     // AJAX Request
     $.ajax({
       url: "{{route('frontend.ratingvideo')}}",
       type: 'GET',
       data: {postid:postid,rating:value,rating:value},
       dataType: 'json',
       success: function(data){
         // Update average
         var average = data['averageRating'];
         $('#avgrating_'+postid).text(average);
		 $('#rate-des').text("Your Rating");
		 
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
       }
     });
   }
  }
 });
  });
 </script> 
 @endif
  <script type="text/javascript">
  
   $(function() {
 $('#rating_{{$video->id}}').barrating('set',{{$myRateing}});
 
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
						<span class="mx-2">></span><a href="{{route('frontend.mylearningListCourse')}}">{{$course->name}}</a>
						</li>
						<li>
							<span class="mx-2">></span>{{$subject->subject_name}}
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
					@if($video->video_id>0)
					
 					<script src="https://player.vimeo.com/api/player.js"></script>
				<div class="course-product-block lesson-video" >

					<div style="padding:56.25% 0 0 0;position:relative;">
					<iframe src="https://player.vimeo.com/video/{{$video->video_id}}?h=40d4b25142&amp;badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;" title="HTML tr Tag"></iframe>
					</div>

					</div>
					@else	
					
					<div class="course-product-block mt-4 lesson-video" id="video_player_box">
					<iframe class="bg-dark" src="{{$video->video_url}}?byline=false"  id="videoPlayer" width="100%" height="315"  frameborder="0" allow="autoplay; fullscreen"  allowfullscreen></iframe>
					
					</div>
					@endif
					<div class="row">
					
					
					<div class="col-lg-12 favdiv">
										<span style="float: left;" id="rate-des">@if($myRateing>0)Your Rating @else Please Rate This Video  @endif</span>
					<span style="float: left;">
						<select class='rating br-readonly' id='rating_{{$video->id}}' data-id='rating_{{$video->id}}'>
						<option value="1" >1</option>
						<option value="2" >2</option>
						<option value="3" >3</option>
						<option value="4" >4</option>
						<option value="5" >5</option>
						</select></span>
						
   
						@if(!empty($video->noteURL()))
					<span style="padding: 0px 10px 0 0;">
                        <a target="_blank" href="{{$video->noteURL()}}"  title="Download Note">Note <i class="fas fa-download"></i></a>
						</span>
                        @endif
					<span ><a class="btn-custom " title="Total Watch"  ><span class="icon  "><i class="fas fa-eye"></i> {{$video_watch_count}}</span></a></span>
					<span id="favBtnHtm"><a class="btn-custom fav-btn" href="{{route('frontend.setFavourites',['video_id'=>$video->id])}}" title="{{($isFav==0)? 'Add favourite' : 'Remove favourite'}}" id="favBtnId" ><span class="icon @if($isFav==1) fav-active @endif "><i class="fas fa-heart"></i></span></a></span>
					
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
								<li class="nav-item" role="presentation">
									<a class="nav-link" href="#profile"  id="profile-tab" data-bs-toggle="tab"
										data-bs-target="#profile"  role="tab" aria-controls="profile"
										aria-selected="false">Average Rating</a>
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
								<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
									<h3>Student Average Rating</h3>
									<div class="review-block-outer">
										<div class="total-rating">
											<h2>{{number_format($averageRating,2)}}</h2>
											<ul class="p-0 m-0 list-unstyled d-flex flex-wrap justify-content-center">
										{!! str_repeat('<li><i class="fa fa-star" aria-hidden="true"></i></li>', $averageRating) !!}
										{!! str_repeat('<li><i class="fa fa-star-o" aria-hidden="true"></i></li>', 5 - $averageRating) !!}
																				
											</ul>
										</div>
										
									</div>
								</div>
							</div>
						</div>
					</div>
					
				</div>
				<div class="col-lg-4">
					<div class="product-details-action-btn-block">
						<h2 class="section-heading">Course content</h2>
						<div class="accordion mt-4" id="accordionExample">
						@foreach($subject->topics as $key1 => $topic)
							@if(count($topic['videos']) > 0)
							<div class="accordion-item">
								<h2 class="accordion-header" id="heading{{$key1}}">
									<button class="accordion-button" type="button" data-bs-toggle="collapse"
										data-bs-target="#collapse{{$key1}}" aria-expanded="true" aria-controls="collapseOne">
										{{$topic->topic_name}}
									</button>
								</h2>
								<div id="collapse{{$key1}}" class="accordion-collapse collapse @if($key1==$tab)show @endif"
									aria-labelledby="headingOne" data-bs-parent="#accordionExample">
									<div class="accordion-body">
										<ul class="mb-0 my-courses-cont-link">
										@foreach($topic['videos'] as $key => $video)
										@if($video->video_upload_type=='main')
											<li>
												<a href="{{route('frontend.mylearningStart',['id'=> $data->id,'subjectId'=>$video->subject_id,'videoUid'=>$video->uuid,'tab'=>$key1])}}" class="link">{{$video->title}}</a>
											</li>
											@endif
											@endforeach
										</ul>
									</div>
								</div>
							</div>
							@endif
						@endforeach	
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>


@endsection
