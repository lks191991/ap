@extends('backend.layouts.layout-2')
@section('scripts')

<script type="text/javascript">
       $(document).ready(function () {

	$('#ResetBtn').on('click', function () {
            window.location.href = "{{route('backend.reports.student.videos.watch')}}";

        });
	
	
    });
</script>
@endsection


@section('content')
@includeif('backend.message')
	

<h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-4">
    <div>Total Videos Watched</div>
</h4>

<div class="card">

    <div class="card-datatable table-responsive">
        <table id="video-list" class="table table-striped table-bordered">
            <thead>
                <tr>
				<form action="" id="filterForm"  action="{{route('backend.reports.student.videos.watch')}}" method = "get"  >
				
                  <th class="align-top">Total Watched</th>
                  
                    <th class="align-top">Course</th>
					 <th  class="align-top">Topic</th>
					<th class="align-top">Video Title
					<div class="row " style="display:none">
					<input type="submit" value="Filter" style="margin-left: 6px; margin-right: 7px;" id="filterBtn" class="btn btn-primary rounded-pill d-block btn-sm">
					<input type="button" value="Reset" id="ResetBtn" class="btn btn-primary rounded-pill d-block btn-sm">
					</div>
                    </th>
					
                 
                   
					</form>
                </tr>
            <tbody>
                @php $i=0; @endphp
                @foreach($fvideos as $studentFavourite)
                <tr>
					  <td>{{$studentFavourite->sumTotal}}</td>
                    <td>{{$studentFavourite->video->subject->subject_name}}</td>
                  <td>{{$studentFavourite->video->topic->topic_name}}</td>
				 
                    <td>{{$studentFavourite->video->title}}</td>
                   
                </tr>
                @endforeach
            </tbody>
            </thead>
        </table>
		{{ $fvideos->appends(request()->input())->links() }}
    </div>
</div>
@endsection
