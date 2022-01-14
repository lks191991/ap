@extends('backend.layouts.layout-2')

@section('content')
<h4 class="font-weight-bold py-3 mb-4">
    <span class="text-muted font-weight-light">Zones /</span> Edit Zone
</h4>

<div class="card mb-4">
    <h6 class="card-header">
        Edit Zone
    </h6>
    <div class="card-body">
        @includeif('backend.message')
        <form action="{{route('backend.zones.update', $zone->id)}}" method = "post" enctype="multipart/form-data">
            @csrf
			{{ method_field('PUT') }}
            <div class="form-group row">
                <label class="col-form-label col-sm-2 text-sm-right">State</label>
                <div class="col-sm-10">
                    <select name="state_id" class="custom-select" disabled required>
                        <option value="">Select State</option>
                        @foreach($states as $state)
                        <option value="{{$state->id}}" @if($zone->state_id == $state->id) selected="selected" @endif>{{$state->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-sm-2 text-sm-right">Name</label>
                <div class="col-sm-10">
                    <input type="text" name="zone_name" placeholder="Zone Name" class="form-control" value="{{$zone->zone_name}}" required>
                </div>
            </div>

           
				<div class="form-group row">
					<label class="col-form-label col-sm-2 text-sm-right"></label>
					<div class="col-sm-10">
						<label class="custom-control custom-checkbox">
							<input type="checkbox" name="status" value="1" class="custom-control-input" @if($zone->status) checked @endif>
								   <span class="custom-control-label">Active</span>
						</label>
					</div>
				</div>
				
				
			
			<div class="form-group row">
                <div class="col-sm-10 ml-sm-auto">
                    <a href = "{{route('backend.zones.index')}}" class="btn btn-danger mr-2">Cancel</a> <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')

@stop