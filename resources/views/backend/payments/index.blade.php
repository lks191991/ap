@extends('backend.layouts.layout-2')

@section('scripts')

<script type="text/javascript">
       $(document).ready(function () {

     
	$('#filterBtn').on('click', function () {
            $('#filterForm').submit()
        });
	
        $('#ResetBtn').on('click', function () {
            window.location.href = "{{route('backend.payments.success')}}";
        });
    });
</script>
@endsection

@section('content')
@includeif('backend.message')
<h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-4">
    <div>Payments</div>
</h4>

<div class="card">

    <div class="card-datatable table-responsive">
        <table id="video-list" class="table table-striped table-bordered">
            <thead>
                <tr>
				<form action="{{route('backend.payments.success')}}" id="filterForm" method = "get" >
                    <th class="align-top" style="width: 100px;">Date</th>
                    <th class="align-top">
                        Name
                        <input type="text" name="your_name" value="{{request('your_name')}}" class="form-control">
                    </th>
                    <th class="align-top">
                        Email
                        <input type="text" value="{{request('emailinput')}}" name="emailinput" class="form-control">
                    </th>
                    <th class="align-top">
                        Contact
                        <input type="text"  name="contactinput" value="{{request('contactinput')}}" class="form-control">
                    </th>
                    <th class="align-top">
                        Course
                    </th>

                    <th class="align-top">
                        Subject
                    </th>
                    <th class="align-top">
                    Actual Price
                    </th>
                    <th class="align-top">
                    Payment
                    </th>
                    <th class="align-top">
                    Discount
                    </th>
                    <th class="align-top">
                    Transaction Id
                    </th>
                   
                    <th class="align-top" style="width: 161px;">Action
					<div class="row">
					<input type="submit" value="Filter" style="margin-left: 6px; margin-right: 7px;" id="filterBtn" class="btn btn-primary btn-sm rounded-pill d-block">
					<input type="button" value="Reset" id="ResetBtn" class="btn btn-primary rounded-pill btn-sm d-block">
					</div>
					</th>
					</form>
                </tr>
            <tbody>
                @php $i=0; @endphp
                @foreach($payments as $payment)
                <tr>
                <td>{{date("d-m-Y", strtotime($payment->created_at))}}</td>
                <td>{{$payment->user->name}}</td>
                <td>{{$payment->user->email}}</td>
                <td>{{$payment->user->mobile}}</td>
                <td>{{$payment->course->name}}</td>
                <td>{{$payment->subject->subject_name}}</td>
                <td>{{$payment->actual_price}}</td>
                <td>{{$payment->price}}</td>
                <td>{{$payment->discount}}</td>
                <td>{{$payment->payment->transaction_id}}</td>
                    <td>
                        <a href ="{{route('backend.payments.show', $payment->id)}}" class="btn btn-default btn-xs icon-btn md-btn-flat article-tooltip" title="View video details"><i class="ion ion-md-eye"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
            </thead>
        </table>
		{{ $payments->appends(request()->input())->links() }}
    </div>
</div>
@endsection
