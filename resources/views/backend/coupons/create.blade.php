@extends('backend.layouts.layout-2')

@section('content')
    <h4 class="font-weight-bold py-3 mb-4">
        <span class="text-muted font-weight-light">Coupon /</span> Create Coupon
    </h4>
	<div class="card mb-4">
        <h6 class="card-header">
            Create Coupon
        </h6>
        <div class="card-body">
			@includeif('backend.message')
            <form action="{{route('backend.coupons.store')}}" method = "post" enctype="multipart/form-data">
			@csrf
			
			<div class="form-group row">
                        <label class="col-form-label col-sm-2 text-sm-right">Type</label>
                        <div class="col-sm-10">
                            <select name="coupon_type" id="coupon_type" class="custom-select" required>
                                <option value="" selected=""  class="d-none">Select Coupon Type</option>
										<option value="fixed" @if(old('coupon_type')=='fixed') selected @endif>Fixed</option>
                                        <option value="percent" @if(old('coupon_type')=='percent') selected @endif >Percent</option>
                               
                            </select>
                        </div>

                    </div>
                    <div class="form-group row">
                    <label class="col-form-label col-sm-2 text-sm-right">Offer Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" placeholder="Offer Name" value="{{ old('name') }}" class="form-control" required>
                    </div>
                </div>
                    <div class="form-group row">
                    <label class="col-form-label col-sm-2 text-sm-right">Amount/Percent</label>
                    <div class="col-sm-10">
                        <input type="text" name="coupon_value" placeholder="Coupon Value" value="{{ old('coupon_value') }}" class="form-control" required>
                    </div>
                </div>
				
                <div class="form-group row">
                    <label class="col-form-label col-sm-2 text-sm-right">Expired Date</label>
                    <div class="col-sm-10">
                        <input type="text" name="expired_at" readonly placeholder="Expired Date"  value="{{ old('expired_at') }}" class="form-control dateinput" required>
                    </div>
                </div>
				
                <div class="form-group row">
                    <label class="col-form-label col-sm-2 text-sm-right">Quantity</label>
                    <div class="col-sm-10">
                        <input type="text" name="quantity" placeholder="Quantity"  value="{{ old('quantity')?old('quantity'):1 }}" class="form-control" required>
                    </div>
                </div>
				
				
                
                <div class="form-group row">
                    <div class="col-sm-10 ml-sm-auto">
						<a href = "{{route('backend.coupons.index')}}" class="btn btn-danger mr-2">Cancel</a> 
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
        $("#institute_type").on("change", function () {
            var category_id = $(this).val();
			
          
            $.ajax({
                type: "POST",
                url: '{{ route("ajax.category.schools") }}',
                data: {'category': category_id, '_token': '{{ csrf_token() }}'},
                success: function (data) {
                    $("#school").html(data.schools);
                }
            });
        });

        $("#school").on("change", function () {
            var school_id = $(this).val();
            var institute_type = $("#institute_type").val();
			
        });
        
});
</script>
@stop