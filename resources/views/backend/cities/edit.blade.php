@extends('backend.layouts.layout-2')

@section('content')
<h4 class="font-weight-bold py-3 mb-4">
    <span class="text-muted font-weight-light">Cities /</span> Edit City
</h4>
<div class="card mb-4">
    <h6 class="card-header">
        Edit City
    </h6>
    <div class="card-body">
        @includeif('backend.message')
        <form action="{{route('backend.cities.update', $city->id)}}" method = "POST">
            @csrf
            @method('PUT')
		
					<div class="form-group row">
                        <label class="col-form-label col-sm-2 text-sm-right">State</label>
                        <div class="col-sm-10">
                            <select name="state_name" id="state_name" class="custom-select" required>
                                <option value="" selected="" disabled="" class="d-none">Select State</option>
                                @foreach($states as $stateid => $state)
										<option value="{{$stateid}}" @if($stateid == $city->state_id ) selected @endif >{{$state}}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
					
                   <div class="form-group row">
                        <label class="col-form-label col-sm-2 text-sm-right">Zone</label>
                        <div class="col-sm-10">
                            <select name="zone_name" id="zones" class="custom-select" required>
                                <option value="" selected="" disabled="" class="d-none">Select Zone</option>
                                @foreach($zones as $zoneid => $zone)
										<option value="{{$zoneid}}" @if($zoneid == $city->zone_id ) selected @endif >{{$zone}}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
				
					
                   <div class="form-group row">
                        <label class="col-form-label col-sm-2 text-sm-right">District</label>
                        <div class="col-sm-10">
                            <select name="district_name" id="districts"  class="custom-select" required>
                                <option value="" selected="" disabled="" class="d-none">Select Zone</option>
                                @foreach($districts as $districtid => $district)
										<option value="{{$districtid}}" @if($districtid == $city->district_id ) selected @endif >{{$district}}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
				
            <div class="form-group row">
                <label class="col-form-label col-sm-2 text-sm-right">City Name</label>
                <div class="col-sm-10">
                    <input type="text" name="city_name" placeholder="City Name" class="form-control" value="{{$city->city_name}}" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-sm-2 text-sm-right"></label>
                <div class="col-sm-10">
                    <label class="custom-control custom-checkbox">
                        <input type="checkbox" name="status" value="1" class="custom-control-input" @if($city->status) checked @endif>
                               <span class="custom-control-label">Active</span>
                    </label>
                </div>
            </div>


            <div class="form-group row">
                <div class="col-sm-10 ml-sm-auto">
                    <a href = "{{route('backend.cities.index')}}" class="btn btn-danger mr-2">Cancel</a> <button type="submit" class="btn btn-primary">Submit</button>
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
          
            $.ajax({
                type: "POST",
                url: '{{ route("ajax.zone.district") }}',
                data: {'zone_id': zone_id, '_token': '{{ csrf_token() }}'},
                success: function (data) {
                    $("#districts").html(data);
                }
            });
        });
		
		
        
 });
</script>
@stop