<!-- Layout navbar -->
<nav class="layout-navbar navbar navbar-expand-lg align-items-lg-center bg-white container-p-x" id="layout-navbar">

    <!-- Brand demo (see resources/assets/css/demo.css) -->
    <a href="/" class="navbar-brand app-brand demo d-lg-none py-0 mr-4">
        <span class="app-brand-logo demo bg-primary">
            <img src="{{asset('images/xt_white.png')}}" width="24">
        </span>
        <span class="app-brand-text demo font-weight-normal ml-2">Saurya</span>
    </a>

    @empty($hide_layout_sidenav_toggle)
    <!-- Sidenav toggle (see resources/assets/css/demo.css) -->
    <div class="layout-sidenav-toggle navbar-nav d-lg-none align-items-lg-center mr-auto">
        <a class="nav-item nav-link px-0 mr-lg-4" href="javascript:void(0)">
            <i class="ion ion-md-menu text-large align-middle"></i>
        </a>
    </div>
    @endempty

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#layout-navbar-collapse">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="navbar-collapse collapse" id="layout-navbar-collapse">
        <!-- Divider -->
        <hr class="d-lg-none w-100 my-2">


    <div class="navbar-nav align-items-lg-center w-100">
		<div class="flex-grow-1 text-center">
			@if(Auth::user()->hasRole('school'))
				@php $profile = Auth::user()->profile; 
					$school_details = \App\Models\School::find($profile->school_id);
				@endphp
				
					@if(isset($school_details->logo) && !empty($school_details->logo) && file_exists(public_path("uploads/schools/$school_details->logo")))
						<img class="school_logo mb-0" style="height:44px;" src='{{asset("uploads/schools/$school_details->logo")}}'>
					@endif
				
			@endif
		</div>
		
            <div class="demo-navbar-user nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                    <span class="d-inline-flex flex-lg-row-reverse align-items-center align-middle">
                        <img src="<?php echo asset(Auth::user()->userData->profile_or_avatar_image); ?>" alt class="d-block rounded-circle" width="24" height="24">
                        <span class="px-1 mr-lg-2 ml-2 ml-lg-0">{{ ucwords(Auth::user()->name)}}</span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="{{route('backend.profile.index')}}" class="dropdown-item"><i class="ion ion-ios-person text-lightest"></i> &nbsp; My profile</a>
                
                    <div class="dropdown-divider"></div>                    
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                      document.getElementById('logout-form').submit();">
                         <i class="ion ion-ios-log-out text-danger"></i> &nbsp; Log Out
                     </a>

                     <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                         @csrf
                     </form>
                </div>
            </div>
        </div>
    </div>
</nav>
<!-- / Layout navbar -->
