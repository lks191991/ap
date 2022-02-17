@extends('backend.layouts.layout-2')

@section('content')
    <h4 class="font-weight-bold py-3 mb-4">
        <span class="text-muted font-weight-light">Students /</span> Edit Student
    </h4>
	<div class="card mb-4">
        <h6 class="card-header">
            Edit Student
        </h6>
        <div class="card-body">
			@includeif('backend.message')
            <form action="{{route('backend.students.update', $student->id)}}" method = "post" enctype="multipart/form-data">
			 @csrf
				@method('PUT')
				
				
				<div class="form-group row">
                    <label class="col-form-label col-sm-2 text-sm-right">First Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="first_name" placeholder="First Name" value="{{ $student->first_name }}" class="form-control" pattern="[A-Za-z ]{1,32}" required>
                    </div>
                </div>
				<div class="form-group row">
                    <label class="col-form-label col-sm-2 text-sm-right">Last Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="last_name" placeholder="Last Name" value="{{ $student->last_name }}" class="form-control" pattern="[A-Za-z ]{1,32}">
                    </div>
                </div>
			
				<div class="form-group row">
                    <label class="col-form-label col-sm-2 text-sm-right">Email</label>
                    <div class="col-sm-10">
                        <input type="email" name="email" id="email" placeholder="Email" value="{{ $student->email }}" class="form-control" required>
                    </div>
                </div>
				
				
				 <div class="form-group row">
                    <label class="col-form-label col-sm-2 text-sm-right">Course Type</label>
                    <div class="col-sm-10">
					<select name="student_type" id="student_type" class="custom-select" required>
						<option value="General" @if($student->student_type == 'General' ) selected @endif>General</option>
						<option value="Vocational" @if($student->student_type == 'Vocational' ) selected @endif>Vocational</option>
					</select>
					</div>
                </div>
				
				<div class="form-group row">
                    <label class="col-form-label col-sm-2 text-sm-right">Mobile</label>
                    <div class="col-sm-10">
                        <input type="text" name="mobile" id="mobile" placeholder="Mobile" value="{{ $student->mobile }}" class="form-control" required>
                    </div>
                </div>
				
			<div class="form-group row">
                    <label class="col-form-label col-sm-2 text-sm-right">Father Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="father_name" id="father_name" placeholder="Father Name" value="{{ $student->father_name }}" class="form-control" required>
                    </div>
                </div>
				<div class="form-group row">
                    <label class="col-form-label col-sm-2 text-sm-right">Branch</label>
                    <div class="col-sm-10">
                        <input type="text" name="branch" id="branch" placeholder="Branch Name" value="{{ $student->branch }}" class="form-control" required>
                    </div>
                </div>
				<div class="form-group row">
                    <label class="col-form-label col-sm-2 text-sm-right">Gender</label>
                    <div class="col-sm-10">
                       	<select name="gender" id="gender" class="custom-select" >
											<option value="" >Select</option>
												<option value="Male" @if('Male' == $student->gender ) selected @endif >Male</option>
													<option value="Female" @if('Female' == $student->gender ) selected @endif >Female</option>
													<option value="Other" @if('Other' == $student->gender ) selected @endif >Other</option>
												</select>
                    </div>
                </div>
				<div class="form-group row">
                    <label class="col-form-label col-sm-2 text-sm-right">DOB</label>
                    <div class="col-sm-10">
					<input type="text" class="form-control" name="dob" id="dob" readonly value="{{ $student->dob }}"  placeholder="DOB" >
                    </div>
                </div>
				
				<div class="form-group row">
                        <label class="col-form-label col-sm-2 text-sm-right">State</label>
                        <div class="col-sm-10">
                            <select name="state_name" id="state_name" class="custom-select" required>
                                <option value="" selected="" disabled="" class="d-none">Select State</option>
                                @foreach($states as $stateid => $state)
										<option value="{{$stateid}}" @if($stateid == $student->state_id ) selected @endif >{{$state}}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
					
                  <div class="form-group row">
                    <label class="col-form-label col-sm-2 text-sm-right">Zone</label>
                    <div class="col-sm-10">
					<select name="zone_name" id="zones" class="custom-select" required>
						<option value="">Select Zone</option>
						  @foreach($zones as $zoneid => $zone)
										<option value="{{$zoneid}}" @if($zoneid == $student->zone_id ) selected @endif >{{$zone}}</option>
                                @endforeach
					</select>
					</div>
                </div>
				
				<div class="form-group row">
                    <label class="col-form-label col-sm-2 text-sm-right">District</label>
                    <div class="col-sm-10">
					<select name="district_name" id="districts" class="custom-select" required>
						<option value="">Select District</option>
						  @foreach($districts as $districtid => $district)
										<option value="{{$districtid}}" @if($districtid == $student->district_id ) selected @endif >{{$district}}</option>
                                @endforeach
					</select>
					</div>
                </div>
					
					<div class="form-group row">
                    <label class="col-form-label col-sm-2 text-sm-right">City</label>
                    <div class="col-sm-10">
					<select name="city_name" id="cities" class="custom-select" required>
						<option value="">Select City</option>
						 @foreach($cities as $citiesid => $city)
										<option value="{{$citiesid}}" @if($citiesid == $student->city_id ) selected @endif >{{$city}}</option>
                                @endforeach
					</select>
					</div>
                </div>
				<div class="form-group row">
                    <label class="col-form-label col-sm-2 text-sm-right">College</label>
                    <div class="col-sm-10">
					<select name="college_name" id="colleges" class="custom-select" required>
						<option value="">Select College</option>
						 @foreach($colleges as $collegeid => $college)
										<option value="{{$collegeid}}" @if($collegeid == $student->college_id ) selected @endif >{{$college}}</option>
                                @endforeach
					</select>
					</div>
                </div>
				<div class="form-group row">
                    <label class="col-form-label col-sm-2 text-sm-right">Profile Photo</label>
                    <div class="col-sm-10">
						@if(isset($student->profile_image) && !empty($student->profile_image))
							<img class="photo mb-2" style="max-width:200px;" src='{{url("$student->profile_image")}}' /><br />
						@endif
                       <input type="file" id="photo" name="photo">
						<small class="form-text mb-4">
							.jpg .png .bmp  |  Size max >= 2mb<br>
						</small>
					</div>
                </div>
				
				<div class="form-group row">
                    <label class="col-form-label col-sm-2 text-sm-right">Status</label>
                    <div class="col-sm-10">
                        <label class="switcher switcher-lg switcher-success">
                                <input type="checkbox" name="status" value="1" class="switcher-input" @if($student->status) checked @endif>
                                <span class="switcher-indicator">
                                    <span class="switcher-yes"><span class="ion ion-md-checkmark"></span></span>
                                    <span class="switcher-no"><span class="ion ion-md-close"></span></span>
                                </span>
                        </label>
                    </div>
                </div>
				
				<div class="form-group row">
                    <div class="col-sm-10 ml-sm-auto">
						<a href = "{{route('backend.students.index')}}" class="btn btn-danger mr-2">Cancel</a> 
						<button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
<script>
    $(document).ready(function () {
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
            var district_id = $(this).val();
          
            $.ajax({
                type: "POST",
                url: '{{ route("ajax.district.city") }}',
                data: {'district_id': district_id, '_token': '{{ csrf_token() }}'},
                success: function (data) {
                    $("#cities").html(data);
                }
            });
        });
        
		$("#cities").on("change", function () {
            var city_id = $(this).val();
          
            $.ajax({
                type: "POST",
                url: '{{ route("ajax.city.colleges") }}',
                data: {'city_id': city_id, '_token': '{{ csrf_token() }}'},
                success: function (data) {
                    $("#colleges").html(data);
                }
            });
        });
        
 });
</script>
@stop