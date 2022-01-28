@extends('backend.layouts.layout-2')

@section('scripts')

<script type="text/javascript">
    $(function () {
		var school_id = "{{request('school')}}";
        var course_id = "{{request('school_course')}}";
        var subject_id = "{{request('subject')}}";      
      
	$("#school").on("change", function () {
            //var school_id = $(this).val();
			var school_id = $('#school option:selected').attr('data-id');
           
				$.ajax({
					type: "POST",
					url: '{{ route("ajax.school.stdfiltercourses",[1]) }}',
					data: {'school_id': school_id, '_token': '{{ csrf_token() }}'},
					success: function (data) {
						$("#school_course").html(data);
						if(course_id){
                            $("#school_course").val(course_id).trigger('change');
                        }
					}
				});
			
        });

if(school_id){
$("#school").val(school_id).trigger('change');
}	
		$("#school_course").on("change", function () {
           // var school_course = $('#school_course').val();
			var school_course = $('#school_course option:selected').attr('data-id');
            if(school_course) {                
                $.ajax({
                    type: "POST",
                    url: '{{ route("ajax.course.subjects",[1]) }}',
                    data: {'course_id' : school_course, '_token': '{{ csrf_token() }}'},
                    success: function (data) {
                        $("#subject").html(data);
						if(subject_id){
                            $("#subject").val(subject_id).trigger('change');
                        }
                    }
                });
            }
        });

		
	
		  
	

		$('#filterBtn').on('click', function () {
            $('#filterForm').submit()
        });
	
    	$('#ResetBtn').on('click', function () {
            window.location.href = "{{route('backend.topics.index')}}";
        });

    });
</script>
@endsection

@section('content')
@includeif('backend.message')
<h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-4">
    <div>Topics</div>
    <a href="{{route('backend.topics.create')}}" class="btn btn-primary rounded-pill d-block"><span class="ion ion-md-add"></span>&nbsp;Create Topic</a>
</h4>

<div class="card">
    
    <div class="card-datatable table-responsive">
        <table id="topic-list" class="table table-striped table-bordered">
            <thead>
                <tr>
				<form action="{{route('backend.topics.index')}}" id="filterForm" method = "get" >
                    <th class="align-top">S.No</th>
                    <th class="align-top">
						Topic Name
						<input type="text" name="topic_name"   value="{{request('topic_name')}}" id="topic_name" class="form-control">
					</th>
					<th class="align-top">
					Course Type
						<select name="school" id="school" class="custom-select">
								<option value="" selected="">All</option>
								@foreach($schools as $id => $type)
									<option value="{{$id}}" data-id="{{$id}}">{{$type}}</option>
								@endforeach
						</select>
					</th>
					<th class="align-top">
						Course
						<select name="school_course" id="school_course" class="custom-select">
										<option value="" selected="">All</option>
						</select>
					</th>
					
					<th class="align-top">
						Subject
						<select name="subject" id="subject" class="custom-select">
									<option value="" selected="">All</option>                        
						</select>
					</th>
                    <th class="align-top">Status</th>
                    <th class="align-top">Action
					<div class="row">
					<input type="submit" value="Filter" style="margin-left: 6px; margin-right: 7px;" id="filterBtn" class="btn btn-primary btn-sm rounded-pill d-block">
					<input type="button" value="Reset" id="ResetBtn" class="btn btn-primary rounded-pill btn-sm d-block">
					</div>

					</th>
					</form>
                </tr>
				
            <tbody>
                @php $i=0; @endphp
                @foreach($topics as $topic)
				@php 
					$course_details = $topic->course_details($topic->subject->course_id);
				@endphp
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{$topic->topic_name}}</td>
					<td>{{$topic->school_details($topic->subject->school_id)}}</td>
					<td>{{$course_details->name}}</td>
					<td>{{$topic->subject->subject_name}}</td>
                    <td>{{$topic->status ? 'Active':'Disabled'}}</td>
                    <td>
                        <a href ="{{route('backend.topics.edit', $topic->id)}}" class="btn btn-default btn-xs icon-btn md-btn-flat article-tooltip" title="Edit"><i class="ion ion-md-create"></i></a>
                        
						<form method="POST" action="{{route('backend.topics.destroy', $topic->id)}}" style="display: inline-block;">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}

                            <button type="submit" onclick="return confirm('You are about to delete this record?')" class="btn btn-default btn-xs icon-btn md-btn-flat article-tooltip" title="Remove"><i class="ion ion-md-close"></i></button>

                        </form>
						
                    </td>
                </tr>
                @endforeach
            </tbody>
            </thead>
        </table>
		{{ $topics->appends(request()->input())->links() }}
    </div>
</div>
@endsection