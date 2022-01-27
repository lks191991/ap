@extends('backend.layouts.layout-2')

@section('scripts')

<script type="text/javascript">
       $(document).ready(function () {

     
	$('#filterBtn').on('click', function () {
            $('#filterForm').submit()
        });
	
        $('#ResetBtn').on('click', function () {
            window.location.href = "{{route('backend.payments.failed')}}";
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
        <table id="video-list" class="table table-striped table-bordered"  style="width:99%">
            <thead>
                <tr>
				<form action="{{route('backend.payments.failed')}}" id="filterForm" method = "get" >
                    <th class="align-top" >Date
                    <input type="text" name="date" value="{{request('date')}}" class="form-control dateinput">
                    </th>
                    <th class="align-top">
                        Transaction Id
                        <input type="text" name="transaction_id" value="{{request('transaction_id')}}" class="form-control">
                    </th>
                    <th class="align-top">
                        Amount
                        <input type="text" value="{{request('amount')}}" name="amount" class="form-control">
                    </th>
                    
                   
                    <th class="align-top" >
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
                <td>{{$payment->transaction_id}}</td>
                <td colspan="2">{{$payment->amount}}</td>
               
              
                </tr>
                @endforeach
            </tbody>
            </thead>
        </table>
		{{ $payments->appends(request()->input())->links() }}
    </div>
</div>
@endsection