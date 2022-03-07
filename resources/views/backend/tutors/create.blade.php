@extends('backend.layouts.layout-2')

@section('content')
    <h4 class="font-weight-bold py-3 mb-4">
        <span class="text-muted font-weight-light">Tutors /</span> Create
    </h4>
	<div class="card mb-4">
        <h6 class="card-header">
            Create Tutor
        </h6>
        <div class="card-body">
			@includeif('backend.message')
            <form action="{{route('backend.tutors.store')}}" method = "post" enctype="multipart/form-data">
			@csrf
				
					
				
				<div class="form-group row">
                    <label class="col-form-label col-sm-2 text-sm-right">First Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="first_name" placeholder="First Name" value="{{ old('first_name') }}" class="form-control" pattern="[A-Za-z ]{1,32}" required>
                    </div>
                </div>
				<div class="form-group row">
                    <label class="col-form-label col-sm-2 text-sm-right">Last Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="last_name" placeholder="Last Name" value="{{ old('last_name') }}"  class="form-control" pattern="[A-Za-z ]{1,32}">
                    </div>
                </div>
				
				<div class="form-group row">
                    <label class="col-form-label col-sm-2 text-sm-right">Email</label>
                    <div class="col-sm-10">
                        <input type="email" name="email" id="email" placeholder="Email" value="{{ old('email') }}" class="form-control" required>
                    </div>
                </div>
				
				
				<div class="form-group row">
                    <label class="col-form-label col-sm-2 text-sm-right">Mobile</label>
                    <div class="col-sm-10">
                        <input type="text" name="mobile" id="mobile" placeholder="Mobile" value="{{ old('mobile') }}" class="form-control" required>
                    </div>
                </div>
				
			
				
				
				<div class="form-group row">
                    <label class="col-form-label col-sm-2 text-sm-right">DOB</label>
                    <div class="col-sm-10">
					<input type="text" class="form-control" name="dob" id="dob" readonly value="{{ old('dob') }}"  placeholder="DOB" >
                    </div>
                </div>
				
				
				<div class="form-group row">
                    <label class="col-form-label col-sm-2 text-sm-right">Profile Photo</label>
                    <div class="col-sm-10">
                       <input type="file" id="photo" name="photo">
						<small class="form-text mb-4">
							.jpg .png .bmp  |  Size max >= 2mb<br>
						</small>
					</div>
                </div>
				
				<div class="form-group row">
                    <label class="col-form-label col-sm-2 text-sm-right">Upload Access</label>
                    <div class="col-sm-10">
                        <label class="switcher switcher-lg switcher-success">
                                <input type="checkbox" name="upload_access" value="1" class="switcher-input">
                                <span class="switcher-indicator">
                                    <span class="switcher-yes"><span class="ion ion-md-checkmark"></span></span>
                                    <span class="switcher-no"><span class="ion ion-md-close"></span></span>
                                </span>
                        </label>
                    </div>
                </div>
				
				<div class="form-group row">
                    <label class="col-form-label col-sm-2 text-sm-right">Status</label>
                    <div class="col-sm-10">
                        <label class="switcher switcher-lg switcher-success">
                                <input type="checkbox" name="status" value="1" class="switcher-input" checked="">
                                <span class="switcher-indicator">
                                    <span class="switcher-yes"><span class="ion ion-md-checkmark"></span></span>
                                    <span class="switcher-no"><span class="ion ion-md-close"></span></span>
                                </span>
                        </label>
                    </div>
                </div>
				
				<div class="form-group row">
                    <div class="col-sm-10 ml-sm-auto">
						<a href = "{{route('backend.tutors.index')}}" class="btn btn-danger mr-2">Cancel</a> 
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