@extends('backend.layouts.layout-2')

@section('content')
    <h4 class="font-weight-bold py-3 mb-4">
        <span class="text-muted font-weight-light">Districts /</span> Create District
    </h4>
	<div class="card mb-4">
        <h6 class="card-header">
            Create District
        </h6>
        <div class="card-body">
			@includeif('backend.message')
            <form action="{{route('backend.districts.store')}}" method = "post" enctype="multipart/form-data">
			@csrf
			
			<div class="form-group row">
                        <label class="col-form-label col-sm-2 text-sm-right">State</label>
                        <div class="col-sm-10">
                            <select name="state_name" id="state_name" class="custom-select" required>
                                <option value="" selected="" disabled="" class="d-none">Select State</option>
                                @foreach($states as $id => $state)
										<option value="{{$id}}" >{{$state}}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
					
			<div class="form-group row">
                    <label class="col-form-label col-sm-2 text-sm-right">Zone</label>
                    <div class="col-sm-10">
					<select name="zone_name" id="zones" class="custom-select" required>
						<option value="">Select Zone</option>
					</select>
					</div>
                </div>
				
		
				
                <div class="form-group row">
                    <label class="col-form-label col-sm-2 text-sm-right">District Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" placeholder="District Name" value="{{ old('name') }}" class="form-control" required>
                    </div>
                </div>
				
			
					
				<div class="form-group row">
                <label class="col-form-label col-sm-2 text-sm-right"></label>
                <div class="col-sm-10">
                    <label class="custom-control custom-checkbox">
                        <input type="checkbox" name="status" value="1" @if(old('status')) checked @endif class="custom-control-input">
                        <span class="custom-control-label">Active</span>
                    </label>
                </div>
            </div>
                
                
                <div class="form-group row">
                    <div class="col-sm-10 ml-sm-auto">
						<a href = "{{route('backend.districts.index')}}" class="btn btn-danger mr-2">Cancel</a> 
						<button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
<!--<script src="{{ mix('/assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>-->

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
            var state_name = $("#state_name").val();
			
        });
        
});
</script>
@stop