@extends('backend.layouts.layout-2')

@section('scripts')

<script type="text/javascript">
    $(function () {

		  $("#state_name").on("change", function () {
            var state_id = $(this).val();
          
            $.ajax({
                type: "POST",
                url: '{{ route("ajax.state.zones") }}',
                data: {'state_id': state_id, '_token': '{{ csrf_token() }}'},
                success: function (data) {
                    $("#zones").html(data);
                }
            });
        });
		
         $("#zones").on("change", function () {
            var zone_id = $(this).val();
          
            $.ajax({
                type: "POST",
                url: '{{ route("ajax.zone.district") }}',
                data: {'zone_id': zone_id, '_token': '{{ csrf_token() }}'},
                success: function (data) {
                    $("#districts").html(data);
                }
            });
        });
		
		
		$("#districts").on("change", function () {
            var zone_id = $(this).val();
          
            $.ajax({
                type: "POST",
                url: '{{ route("ajax.district.city") }}',
                data: {'district_id': zone_id, '_token': '{{ csrf_token() }}'},
                success: function (data) {
                    $("#cities").html(data);
                }
            });
        });
		
		var table = $('#college-list').DataTable({
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
		
		$('#college_name').on('keyup', function(){
	    
	    regExSearch = this.value;
		//alert(regExSearch);
		table.column(1).search(regExSearch, true, false).draw();
		  // table.search(this.value, true, false).draw();   
		});
	
	$('#state_name').on('change', function(){		
	   //alert('gdfgfd');
	   if($("#state_name option:selected").text()=='All')
	   {
		   var value = '';
	   }
	   else
	   {
		   var value = $("#state_name option:selected").text();
	   }
		regExSearch = value +'\\s*$';
		//alert(regExSearch)
		table.column(2).search(regExSearch, true, false).draw();
	   //table.search(this.value, true, false).draw();   
		});
		
		$('#zones').on('change', function(){		
	   //alert('gdfgfd');
		//regExSearch = this.text +'\\s*$';
		 if($("#zones option:selected").text()=='All')
	   {
		   var value = '';
	   }
	   else
	   {
		   var value = $("#zones option:selected").text();
	   }
		regExSearch = value +'\\s*$';
		//alert(regExSearch);
		table.column(3).search(regExSearch, true, false).draw();
	   //table.search(this.value, true, false).draw();   
		});
	   
	   $('#districts').on('change', function(){		
		   //alert('gdfgfd');
			//regExSearch = this.text +'\\s*$';
			 if($("#districts option:selected").text()=='All')
	   {
		   var value = '';
	   }
	   else
	   {
		   var value = $("#districts option:selected").text();
	   }
		regExSearch = value +'\\s*$';
			table.column(4).search(regExSearch, true, false).draw();
		   //table.search(this.value, true, false).draw();   
		});
		
		$('#cities').on('change', function(){		
		   //alert('gdfgfd');
		   //regExSearch = this.text +'\\s*$';
		   if($("#cities option:selected").text()=='All')
	   {
		   var value = '';
	   }
	   else
	   {
		   var value = $("#cities option:selected").text();
	   }
		regExSearch = value +'\\s*$';
		   table.column(5).search(regExSearch, true, false).draw();
		   table.search(this.value, true, false).draw();   
		});
		
        
    });
</script>
@endsection

@section('content')
@includeif('backend.message')
<h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-4">
    <div>Colleges</div>
  <a href="{{route('backend.colleges.create')}}" class="btn btn-primary rounded-pill d-block"><span class="ion ion-md-add"></span>&nbsp;Create College</a>
</h4>

<div class="card">
    
    <div class="card-datatable table-responsive">
        <table id="college-list" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="align-top">S.No</th>
                    <th class="align-top">
					College Name
					<input type="text" name="college_name" id="college_name" class="form-control">
					</th>
					<th class="align-top">
					State Name
					<select name="state_name" id="state_name" class="custom-select" required>
							<option value="" selected="">All</option>
							@foreach($states as $id => $type)
								<option value="{{$id}}" data-id="{{$id}}">{{$type}}</option>
							@endforeach
						 </select>
					</th>
					<th style="min-width: 7rem" class="align-top">
						Zone
						<select name="zone_name" id="zones" class="custom-select" required>
						<option value="">Select Zone</option>
					</select>
					</th>
					<th style="min-width: 7rem" class="align-top">
						District
						<select name="district_name" id="districts" class="custom-select" required>
						<option value="">Select District</option>
					</select>
					</th>
					
					<th style="min-width: 7rem" class="align-top">
						City
						<select name="city_name" id="cities" class="custom-select" required>
						<option value="">Select City</option>
					</select>
					</th>
					 
                    <th class="align-top">Status</th>
                    <th class="align-top">Action</th>
                </tr>
            <tbody>
                @php $i=0; @endphp
                @foreach($colleges as $college)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{$college->name}}</td>
					<td>@if(isset($college->state) && !empty(($college->state->name))){{$college->state->name}}@endif</td>
					<td>@if(isset($college->zone) && !empty(($college->zone->zone_name))){{$college->zone->zone_name}}@endif</td>
					<td>@if(isset($college->district) && !empty(($college->district->name))){{$college->district->name}}@endif</td>
					<td>@if(isset($college->city) && !empty(($college->city->city_name))){{$college->city->city_name}}@endif</td>
                    <td>{{$college->status ? 'Active':'Disabled'}}</td>
                   <td>
                        <a href ="{{route('backend.colleges.edit', $college->id)}}" class="btn btn-default btn-xs icon-btn md-btn-flat article-tooltip" title="Edit"><i class="ion ion-md-create"></i></a>
                        @role('admin')
						<form method="POST" action="{{route('backend.colleges.destroy', $college->id)}}" style="display: inline-block;">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}

                            <button type="submit" onclick="return confirm('You are about to delete this record?')" class="btn btn-default btn-xs icon-btn md-btn-flat article-tooltip" title="Remove"><i class="ion ion-md-close"></i></button>

                        </form>
						@endrole
                    </td>
                </tr>
                @endforeach
            </tbody>
            </thead>
        </table>
    </div>
</div>
@endsection