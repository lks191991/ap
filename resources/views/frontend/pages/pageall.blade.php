@extends('frontend.layouts.app')
@php 
$settings= SiteHelpers::contactDetails();
 @endphp
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
							<span class="mx-2">></span>{!!$page->title!!}
						</li>
					</ul>
				</div>
			</div>
		</div>
	</section>

	<!-- Breadcrumbs Ends-->
<!-- Contact Us -->

<section class="about-us-section bg-light py-4 border-top">
		<div class="container">
			<div class="white-cont-block">
				<div class="row">
					<div class="col-md-12">
						<h2 class="page-heading add-border-bottom">{!!$page->title!!}</h2>
						<p class="description">{!!$page->page_content!!}</p>

					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Contact Us Ends-->


@endsection