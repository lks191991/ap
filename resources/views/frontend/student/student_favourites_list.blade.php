@extends('frontend.layouts.app')

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
							<span class="mx-2">></span>Video Favourite List
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
							<h1 class="section-heading with-bottom-line text-center">Video Favourite List</h1>
						</div>
						<div class="dashboard-detail-outer pt-4 payment-history">
							
							<div class="table-responsive">
								<table class="table custom-table mt-4">
									<thead>
										<tr>
											<th scope="col">Subject</th>
											<th scope="col">Topic</th>
											<th scope="col">Video</th>
											<th class="col">Action</th>
										</tr>
									</thead>
									<tbody>
									@foreach($studentFavourites as $studentFavourite)
									@php
									$data = \App\Models\UserSubscription::where("user_id",$studentFavourite->student_id)->where("subject_id",$studentFavourite->video->subject_id)->first();
									
									@endphp
										<tr>
											
											<td>{{$studentFavourite->video->subject->subject_name}}</td>
											<td>{{$studentFavourite->video->topic->topic_name}}</td>
											<th scope="row"><a href="{{route('frontend.mylearningStart',['id'=> $data->id,'subjectId'=>$studentFavourite->video->subject_id,'videoUid'=>$studentFavourite->video->uuid])}}" class="link">{{$studentFavourite->video->title}}</a></th>
											
											<td>
												<form method="POST" action="{{route('frontend.topic.destroy', $studentFavourite->id)}}" style="display: inline-block;">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}

                            <button type="submit" onclick="return confirm('You are about to delete this record?')" class=btn btn-sm delete-btn" title="Remove"><i
														class="far fa-trash-alt"></i></button>

                        </form>
											</td>
										</tr>
										
										@endforeach
									</tbody>
								</table>
								
							</div>
							<div class="row">
								<div class="pagination-block mt-md-5 mt-4">
									<nav aria-label="...">
										{{ $studentFavourites->links() }}
									</nav>
								</div> 
				
			</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

@endsection