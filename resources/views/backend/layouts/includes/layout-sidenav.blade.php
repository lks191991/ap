@php
$routeName = Route::currentRouteName();
@endphp

<!-- Layout sidenav -->
<div id="layout-sidenav" class="{{ isset($layout_sidenav_horizontal) ? 'layout-sidenav-horizontal sidenav-horizontal container-p-x flex-grow-0' : 'layout-sidenav sidenav-vertical' }} sidenav bg-sidenav-theme">
    @empty($layout_sidenav_horizontal)
    <!-- Brand demo (see assets/css/demo/demo.css) -->
    <div class="app-brand demo">
        <span class="app-brand-logo demo bg-primary">
            <img src="{{asset('images/xt_white.png')}}" width="24" />
        </span>
        <a href="/admin" class="app-brand-text demo sidenav-text font-weight-normal ml-2">{{ config('app.name', 'Saurya')}}</a>
        <a href="javascript:void(0)" class="layout-sidenav-toggle sidenav-link text-large ml-auto">
            <i class="ion ion-md-menu align-middle"></i>
        </a>
    </div>

    <div class="sidenav-divider mt-0"></div>
    @endempty

    <!-- Links -->
    <ul class="sidenav-inner{{ empty($layout_sidenav_horizontal) ? ' py-1' : '' }}">

        <!-- Dashboards -->
        <li class="sidenav-item{{ strpos($routeName, 'backend.dashboard') !== false ? ' active' : '' }}">
            <a href="{{ route('backend.dashboard') }}" class="sidenav-link"><i class="sidenav-icon ion ion-md-speedometer"></i><div>Dashboard</div></a>
            <!--
                        <ul class="sidenav-menu">
                            <li class="sidenav-item{{ $routeName == 'backend.dashboard' ? ' active' : '' }}">
                                <a href="{{ route('backend.dashboard') }}" class="sidenav-link"><div>Dashboard</div></a>
                            </li>
                        </ul> -->
        </li>
        @role('admin|subadmin')
		<li class="sidenav-item {{ (strpos($routeName, 'backend.categories') !== false)  ? ' open' : '' }}  {{(strpos($routeName, 'backend.schools') !== false)  ? ' open' : '' }}  {{(strpos($routeName, 'backend.courses') !== false)  ? ' open' : '' }}  {{(strpos($routeName, 'backend.classes') !== false)  ? ' open' : '' }}  {{(strpos($routeName, 'backend.subjects') !== false)  ? ' open' : '' }} {{(strpos($routeName, 'backend.topics') !== false)  ? ' open' : '' }} {{(strpos($routeName, 'backend.video') !== false)  ? ' open' : '' }}" style="">
            <a href="javascript:void(0)" class="sidenav-link sidenav-toggle"><i class="sidenav-icon ion ion-md-cube"></i>
              <div>Courses Manager</div>
            </a>

            <ul class="sidenav-menu">
        <li class="sidenav-item{{ (strpos($routeName, 'backend.categories') !== false) ? ' active' : '' }}">
            <a href="{{ route('backend.categories.index') }}" class="sidenav-link"><i class="sidenav-icon fas fa-list"></i><div>Institutions</div></a>
        </li>
		

        <li class="sidenav-item{{ ((strpos($routeName, 'backend.schools') !== false) || (strpos($routeName, 'backend.school') !== false)) ? ' active' : '' }}">
            <a href="{{ route('backend.schools') }}" class="sidenav-link"><i class="sidenav-icon fas fa-school"></i><div>Schools</div></a>
        </li>
        
		
      
        <li class="sidenav-item{{ ((strpos($routeName, 'backend.courses') !== false) || (strpos($routeName, 'backend.course') !== false)) ? ' active' : '' }}">
            <a href="{{ route('backend.courses') }}" class="sidenav-link"><div><i class="sidenav-icon fas fa-book-reader"></i>Courses</div></a>
        </li>

        <li class="sidenav-item{{ (strpos($routeName, 'backend.classes') !== false)  ? ' active' : '' }}">
            <a href="{{ route('backend.classes.index') }}" class="sidenav-link"><i class="sidenav-icon fas fa-chalkboard"></i><div>Classes</div></a>
        </li>

		

        <li class="sidenav-item{{ (strpos($routeName, 'backend.subjects') !== false)  ? ' active' : '' }}">
            <a href="{{ route('backend.subjects.index') }}" class="sidenav-link"><i class="sidenav-icon fas fa-book"></i><div>Subjects</div></a>
        </li>

        <li class="sidenav-item{{ (strpos($routeName, 'backend.topics') !== false)  ? ' active' : '' }}">
            <a href="{{ route('backend.topics.index') }}" class="sidenav-link"><i class="sidenav-icon fas fa-tags"></i><div>Topics</div></a>

        </li> 
        @endrole      

        <li class="sidenav-item{{ (strpos($routeName, 'backend.video') !== false)  ? ' active' : '' }}">
            <a href="{{ route('backend.videos.index') }}" class="sidenav-link"><i class="sidenav-icon fas fa-video"></i><div>Videos</div></a>
        </li>
  </ul>
    </li> 
		<li class="sidenav-item {{ (strpos($routeName, 'backend.students') !== false)  ? ' open' : '' }}  {{(strpos($routeName, 'backend.tutors') !== false)  ? ' open' : '' }}" style="">
            <a href="javascript:void(0)" class="sidenav-link sidenav-toggle"><i class="sidenav-icon fas fa-user"></i>
              <div>Users Manager</div>
            </a>
          <ul class="sidenav-menu">
        <li class="sidenav-item{{ (strpos($routeName, 'backend.students') !== false)  ? ' active' : '' }}">
            <a href="{{ route('backend.students.index') }}" class="sidenav-link"><i class="sidenav-icon fas fa-user-graduate"></i><div>Students</div></a>
        </li>

		<li class="sidenav-item{{ (strpos($routeName, 'backend.tutors') !== false)  ? ' active' : '' }}">
            <a href="{{ route('backend.tutors.index') }}" class="sidenav-link"><i class="sidenav-icon fas fa-chalkboard-teacher"></i><div>Tutors</div></a>
        </li>
		
		 </ul>
       </li>
		<li class="sidenav-item {{ (strpos($routeName, 'backend.ContactInquiries') !== false)  ? ' open' : '' }}  {{(strpos($routeName, 'backend.newsletters') !== false)  ? ' open' : '' }}" style="">
            <a href="javascript:void(0)" class="sidenav-link sidenav-toggle"><i class="sidenav-icon fa fa-envelope"></i>
              <div>Inquiry</div>
            </a>
          <ul class="sidenav-menu">
        <li class="sidenav-item{{ (strpos($routeName, 'backend.ContactInquiries') !== false)  ? ' active' : '' }}">
            <a href="{{ route('backend.ContactInquiries') }}" class="sidenav-link"><i class="sidenav-icon fas fa-phone"></i><div> Contact Us</div></a>
        </li>

		<li class="sidenav-item{{ (strpos($routeName, 'backend.newsletters') !== false)  ? ' active' : '' }}">
            <a href="{{ route('backend.newsletters') }}" class="sidenav-link"><i class="sidenav-icon fas fa-rocket"></i><div> Subscription</div></a>
        </li>
		
		 </ul>
       </li>
      <li class="sidenav-item {{ (strpos($routeName, 'backend.states') !== false)  ? ' open' : '' }}  {{(strpos($routeName, 'backend.zones') !== false)  ? ' open' : '' }}  {{(strpos($routeName, 'backend.districts') !== false)  ? ' open' : '' }}  {{(strpos($routeName, 'backend.cities') !== false)  ? ' open' : '' }}  {{(strpos($routeName, 'backend.colleges') !== false)  ? ' open' : '' }}" style="">
            <a href="javascript:void(0)" class="sidenav-link sidenav-toggle"><i class="sidenav-icon fa fa-map-marker"></i>
              <div>Manage Locations</div>
            </a>

            <ul class="sidenav-menu">
			  <li class="sidenav-item{{ (strpos($routeName, 'backend.states') !== false)  ? ' active' : '' }}">
            <a href="{{ route('backend.states.index') }}" class="sidenav-link"><i class="sidenav-icon fas fa-list"></i><div>State</div></a>
        </li>
             
               <li class="sidenav-item{{ (strpos($routeName, 'backend.zones') !== false)  ? ' active' : '' }}">
            <a href="{{ route('backend.zones.index') }}" class="sidenav-link"><i class="sidenav-icon fas fa-list"></i><div>Zone</div></a>
        </li>
        <li class="sidenav-item{{ (strpos($routeName, 'backend.districts') !== false)  ? ' active' : '' }}">
            <a href="{{ route('backend.districts.index') }}" class="sidenav-link"><i class="sidenav-icon fas fa-list"></i><div>Distict</div></a>
        </li>
        <li class="sidenav-item{{ (strpos($routeName, 'backend.cities') !== false)  ? ' active' : '' }}">
            <a href="{{ route('backend.cities.index') }}" class="sidenav-link"><i class="sidenav-icon fas fa-list"></i><div>City</div></a>
        </li>
        <li class="sidenav-item{{ (strpos($routeName, 'backend.colleges') !== false)  ? ' active' : '' }}">
            <a href="{{ route('backend.colleges.index') }}" class="sidenav-link"><i class="sidenav-icon fas fa-list"></i><div>College</div></a>
        </li>
            </ul>
          </li>
        <li class="sidenav-item {{ (strpos($routeName, 'backend.reports.favourited.videos.list') !== false)  ? ' open' : '' }}  {{ (strpos($routeName, 'backend.reports.student.videos.watch') !== false)  ? ' open' : '' }} {{ (strpos($routeName, 'backend.reports.total.videos.watch') !== false)  ? ' open' : '' }}" style="">
            <a href="javascript:void(0)" class="sidenav-link sidenav-toggle"><i class="sidenav-icon fas fa-user"></i>
              <div>Reports</div>
            </a>
          <ul class="sidenav-menu">
        <li class="sidenav-item{{ (strpos($routeName, 'backend.reports.favourited.videos.list') !== false)  ? ' active' : '' }}">
            <a href="{{ route('backend.reports.favourited.videos.list') }}" class="sidenav-link"><i class="sidenav-icon fas fa-video"></i><div>Favourited Videos</div></a>
        </li>
		
		<li class="sidenav-item{{ (strpos($routeName, 'backend.reports.student.videos.watch') !== false)  ? ' active' : '' }}">
			<a href="{{ route('backend.reports.student.videos.watch') }}" class="sidenav-link"><i class="sidenav-icon fas fa-eye"></i><div>Videos Watch</div></a>
        </li>

		<li class="sidenav-item{{ (strpos($routeName, 'backend.reports.total.videos.watch') !== false)  ? ' active' : '' }}">
			<a href="{{ route('backend.reports.total.videos.watch') }}" class="sidenav-link"><i class="sidenav-icon fas fa-eye"></i><div>Total Videos Watch</div></a>
        </li>
		
		 </ul>
       </li>
	@role('admin')	
		<li class="sidenav-item{{ (strpos($routeName, 'backend.settings') !== false)  ? ' active' : '' }}">
            <a href="{{ route('backend.settings.index') }}" class="sidenav-link"><i class="sidenav-icon fas fa-cog"></i><div>Settings</div></a>
        </li>
    @endrole

    </ul>
</div>
<!-- / Layout sidenav -->
