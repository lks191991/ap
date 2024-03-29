@extends('backend.layouts.layout-2')

@section('scripts')
    <!-- Dependencies -->
    <script src="{{ mix('/assets/vendor/libs/chartjs/chartjs.js') }}"></script>
    
    <script src="{{ mix('/assets/js/dashboards_dashboard-1.js') }}"></script>
@endsection

@section('content')
    <h4 class="font-weight-bold py-3 mb-4">
        Dashboard
        <div class="text-muted text-tiny mt-1"><small class="font-weight-normal">Today is {{date('l, jS F Y')}}</small></div>
    </h4>

    <!-- Counters -->
    <div class="row">
        <div class="col-sm-6 col-xl-4">

            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-list"></i>
                        <div class="ml-4">
                            <div class="text-muted small">Institutions</div>
                            <div class="text-large">{{$institute_count}}</div>
                        </div>
                    </div>
                </div> 
            </div>

        </div>
        <div class="col-sm-6 col-xl-4">

            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-school"></i>
                        <div class="ml-4">
                            <div class="text-muted small">Schools</div>
                            <div class="text-large">{{$school_count}}</div>
                        </div>
                    </div>
                </div>
			</div>
        </div>
       
        <div class="col-sm-6 col-xl-4">

            <div class="card mb-4">
                
				<div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-graduation-cap"></i>
                        <div class="ml-4">
                            <div class="text-muted small">Courses</div>
                            <div class="text-large">{{$courses_count}}</div>
                        </div>
                    </div>
                </div>
				
            </div>

        </div>
    </div>
	
	<div class="row">
        <div class="col-sm-6 col-xl-4">

            <div class="card mb-4">
			
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-university"></i>
                        <div class="ml-4">
                            <div class="text-muted small">Classes</div>
                            <div class="text-large">{{$classes_count}}</div>
                        </div>
                    </div>
                </div>
				
            </div>

        </div>
      
        <div class="col-sm-6 col-xl-4">

            <div class="card mb-4">
			
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="sidenav-icon fas fa-book"></i>
                        <div class="ml-4">
                            <div class="text-muted small">Subjects</div>
                            <div class="text-large">{{$subject_count}}</div>
                        </div>
                    </div>
                </div>
				
            </div>

        </div>
        <div class="col-sm-6 col-xl-4">

            <div class="card mb-4">
			
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="sidenav-icon fas fa-tags"></i>
                        <div class="ml-4">
                            <div class="text-muted small">Topics</div>
                            <div class="text-large">{{$topic_count}}</div>
                        </div>
                    </div>
                </div>
				
            </div>

        </div>
    </div>
	
	<div class="row">
        <div class="col-sm-6 col-xl-4">

            <div class="card mb-4">
			
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="sidenav-icon fas fa-video"></i>
                        <div class="ml-4">
                            <div class="text-muted small">Videos</div>
                            <div class="text-large">{{$videos_count}}</div>
                        </div>
                    </div>
                </div>
				
            </div>

        </div>
        <div class="col-sm-6 col-xl-4">

            <div class="card mb-4">
			
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="lnr lnr-users display-4"></div>
                        <div class="ml-4">
                            <div class="text-muted small">Students</div>
                            <div class="text-large">{{$student_count}}</div>
                        </div>
                    </div>
                </div>
				
            </div>

        </div>
        <div class="col-sm-6 col-xl-4">

            <div class="card mb-4">
			
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="lnr lnr-users display-4"></div>
                        <div class="ml-4">
                            <div class="text-muted small">Tutors</div>
                            <div class="text-large">{{$tutor_count}}</div>
                        </div>
                    </div>
                </div>
				
            </div>

        </div>
        <div class="col-sm-6 col-xl-6">

            <div class="card mb-6">
			
                <div class="card-body">
                <div class="text-muted">Top 10 Student ZoneWise</div>
        <div class="card-datatable table-responsive">
            
        <table id="video-list" class="table table-striped table-bordered">
            <thead>
                <tr>
				<th class="align-top">Zone
					</th>
                    <th class="align-top">
                        Total Student
                    </th>
                    
                  
                </tr>
            <tbody>
                @foreach($students as $student)
                <tr>
                   
                  <td>{{$student->zone->zone_name}}</td>
                    <td>{{$student->total}}</td>
                  
                </tr>
                @endforeach
            </tbody>
            </thead>
        </table>
    </div>
    </div>
    </div>
    </div>
    <div class="col-sm-6 col-xl-6">

<div class="card mb-6">

    <div class="card-body">
    <div class="text-muted">Top 10 Tutors ZoneWise</div>
<div class="card-datatable table-responsive">

<table id="video-list" class="table table-striped table-bordered">
<thead>
    <tr>
    <th class="align-top">Zone
        </th>
        <th class="align-top">
            Total Tutors
        </th>
        
      
    </tr>
<tbody>
    @foreach($tutors as $tutor)
    <tr>
       
      <td>{{$tutor->zone->zone_name}}</td>
        <td>{{$tutor->total}}</td>
      
    </tr>
    @endforeach
</tbody>
</thead>
</table>
</div>
</div>
</div>
</div>
    </div>
    <!-- / Counters -->

@endsection