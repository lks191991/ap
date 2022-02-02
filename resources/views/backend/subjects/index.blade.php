@extends('backend.layouts.layout-2')

@section('scripts')

<script type="text/javascript">
    $(function () {
	
		
		var table = $('#subject-list').DataTable({
		   "order": [[ 0, "asc" ]],
				"columns": [
				  null,
				  { "orderable": false },
				  { "orderable": false },
				  { "orderable": false },
				  { "orderable": false },
				  { "orderable": false },
				  null,
				  { "orderable": false }
				],
				dom: 'lrtip'
		});
		
		$('#subject_name').on('keyup', function(){
	    //alert('gdfgfd');
	    regExSearch = this.value;
		table.column(1).search(regExSearch, true, false).draw();
		  // table.search(this.value, true, false).draw();   
		});
	
	   
	   $('#school_course').on('change', function(){		
		   //alert('gdfgfd');
			regExSearch = this.value +'\\s*$';
			table.column(2).search(regExSearch, true, false).draw();
		   //table.search(this.value, true, false).draw();   
		});
		
	
    });
</script>
@endsection

@section('content')
@includeif('backend.message')
<h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-4">
    <div>Courses</div>
  <a href="{{route('backend.subjects.create')}}" class="btn btn-primary rounded-pill d-block"><span class="ion ion-md-add"></span>&nbsp;Create Course</a>
</h4>

<div class="card">
    
    <div class="card-datatable table-responsive">
        <table id="subject-list" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="align-top">S.No</th>
                    <th class="align-top">
					Course Name
					<input type="text" name="subject_name" id="subject_name" class="form-control">
					</th>
					
					<th style="min-width: 7rem" class="align-top">
					School Name
						<select name="course" id="school_course" class="custom-select" required>
									<option value="" selected="">All</option>
									@foreach($classes  as $id => $type)
								<option value="{{$type}}" data-id="{{$id}}">{{$type}}</option>
							@endforeach
						</select>
					</th>
					
					   <th class="align-top">
					Price
					</th>
                    <th class="align-top">Status</th>
                    <th class="align-top">Action</th>
                </tr>
            <tbody>
                @php $i=0; @endphp
                @foreach($subjects as $subject)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{$subject->subject_name}}</td>
					<td>@if(isset($subject->subject_class->class_name) && !empty(($subject->subject_class->class_name))){{$subject->subject_class->class_name}}@endif</td>
					<td>{{$subject->subject_price}}</td>
                    <td>{{$subject->status ? 'Active':'Disabled'}}</td>
                   <td>
                        <a href ="{{route('backend.subjects.edit', $subject->id)}}" class="btn btn-default btn-xs icon-btn md-btn-flat article-tooltip" title="Edit"><i class="ion ion-md-create"></i></a>
                        @role('admin')
						<form method="POST" action="{{route('backend.subjects.destroy', $subject->id)}}" style="display: inline-block;">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}

                            <button type="submit" onclick="return confirm('You are about to delete this record?')" class="btn btn-default btn-xs icon-btn md-btn-flat article-tooltip" title="Remove"><i class="ion ion-md-close"></i></button>

                        </form>
						@endrole
						<a href ="{{route('backend.subjects.show', $subject->id)}}" style="display:none" class="btn btn-default btn-xs icon-btn md-btn-flat article-tooltip" title="View subject details"><i class="ion ion-md-eye"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
            </thead>
        </table>
    </div>
</div>
@endsection