@extends('backend.layouts.layout-2')
@section('scripts')

<script type="text/javascript">
       $(document).ready(function () {

	$('#ResetBtn').on('click', function () {
            window.location.href = "{{route('backend.reports.total.student.tutor')}}";

        });
	
	
    });
</script>
@endsection


@section('content')
@includeif('backend.message')
	

<h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-4">
    <div>Total Student and Tutor</div>
</h4>

<div class="card">

    <div class="card-datatable table-responsive">
        <table id="video-list" class="table table-striped table-bordered">
            <thead>
                <tr>
				<form  id="filterForm"  action="{{route('backend.reports.total.student.tutor')}}" method = "get"  >
				<th class="align-top">
					State Name
					<select name="state" id="state_name" class="custom-select" >
							<option value="" selected="">All</option>
							@foreach($states as $id => $type)
								<option value="{{$id}}" @if(request('state')==$id) selected @endif data-id="{{$id}}">{{$type}}</option>
							@endforeach
						 </select>
					</th>
                  
                    <th style="min-width: 7rem" class="align-top">
						Zone
						<select name="zone" id="zones" class="custom-select" >
						<option value="">Select Zone</option>
                        @foreach($zones as $zoneid => $type)
								<option value="{{$zoneid}}"  data-id="{{$zoneid}}"@if(request('zone')==$zoneid) selected @endif>{{$type}}</option>
							@endforeach
					</select>
					</th>
                    <th style="min-width: 7rem" class="align-top">
						District
						<select name="district" id="districts" class="custom-select" >
						<option value="">Select District</option>
                        @foreach($districts as $id => $type)
								<option value="{{$id}}" data-id="{{$id}}" @if(request('district')==$id) selected @endif>{{$type}}</option>
							@endforeach
					</select>
					</th>
                    <th style="min-width: 7rem" class="align-top">
						City
						<select name="city" id="cities" class="custom-select" >
						<option value="">Select City</option>
                        @foreach($cities as $id => $type)
								<option value="{{$id}}" data-id="{{$id}}" @if(request('city')==$id) selected @endif>{{$type}}</option>
							@endforeach
					</select>
					</th>
                    <th style="min-width: 7rem" class="align-top">
                    College Name
						<select name="colleges" id="cities" class="custom-select" >
						<option value="">Select College</option>
                        @foreach($colleges as $id => $type)
								<option value="{{$id}}" data-id="{{$id}}" @if(request('colleges')==$id) selected @endif>{{$type}}</option>
							@endforeach
					</select>
					</th>
					<th class="align-top">Video Title
					<div class="row">
					<input type="submit" value="Filter" style="margin-left: 6px; margin-right: 7px;" id="filterBtn" class="btn btn-primary rounded-pill d-block btn-sm">
					<input type="button" value="Reset" id="ResetBtn" class="btn btn-primary rounded-pill d-block btn-sm">
					</div>
                    </th>
					
                 
                   
					</form>
                </tr>
                </thead>
        </table>
                <table id="video-list" class="table table-striped table-bordered">
            <thead>
                <tr>
				<th class="align-top">Total Student
					</th>
                  
                    <th class="align-top">
                    Total Tutor
                    </th>
                 
                   
					</form>
                </tr>
            <tbody>
              
                <tr>
                  
                  <td>{{$student}}</td>
				 
                    <td>{{$tutor}}</td>
                   
                </tr>
            </tbody>
            </thead>
        </table>
    </div>
</div>
@endsection
