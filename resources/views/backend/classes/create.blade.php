@extends('backend.layouts.layout-2')

@section('content')
<h4 class="font-weight-bold py-3 mb-4">
    <span class="text-muted font-weight-light">Schools /</span> Create School
</h4>
<div class="card mb-4">
    <h6 class="card-header">
        Create School
    </h6>
    <div class="card-body">
        @includeif('backend.message')
        <form action="{{route('backend.classes.store')}}" method = "post">
            @csrf
			
                 
				
                    <div class="form-group row">
                        <label class="col-form-label col-sm-2 text-sm-right">Institute</label>
                        <div class="col-sm-10">
                            <select name="institute" id="school_course" class="custom-select" required>
                                <option value="" disabled selected="">Select Institute</option>
                                @foreach($courses as $id => $type)
                                <option value="{{$id}}">{{$type}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
            <div class="form-group row">
                <label class="col-form-label col-sm-2 text-sm-right">School Name</label>
                <div class="col-sm-10">
                    <input type="text" name="class_name" placeholder="School Name" value="{{old('class_name')}}" class="form-control" required>
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
                    <a href = "{{route('backend.classes.index')}}" class="btn btn-danger mr-2">Cancel</a> <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')

@stop	