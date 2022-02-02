@extends('backend.layouts.layout-2')

@section('scripts')

<script type="text/javascript">
    $(function () {
        $('#coupon-list').dataTable(
			{
            "order": [[ 0, "asc" ]],
            "columns": [
            { "orderable": true },
            { "orderable": false },
            { "orderable": false },
            { "orderable": false },
            { "orderable": false },
            { "orderable": false },
            { "orderable": false },
            { "orderable": false },
            ],
			initComplete: function () {
            this.api().columns([3]).every( function () {
                var column = this;
                var select = $('<select class="custom-select"><option value="">All</option></select>')
                    .appendTo( $(column.header()) )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        }
          }
		);
    });
</script>
@endsection

@section('content')

@includeif('backend.message')

<h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-4">
    <div>Coupons</div>
    <a href="{{route('backend.coupons.create')}}" class="btn btn-primary rounded-pill d-block"><span class="ion ion-md-add"></span>&nbsp;Create Coupon</a>
</h4>

<div class="card">
    <div class="card-datatable table-responsive">
        <table id="coupon-list" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="align-top">S.No</th>
                    <th style="min-width: 12rem" class="align-top">Name</th>
                    <th style="min-width: 5rem" class="align-top">Code</th>
                    <th style="min-width: 6rem" class="align-top">Type</th>
                    <th style="min-width: 3rem" class="align-top">Value</th>
                    <th style="min-width: 5rem" class="align-top">Expired</th>
                    <th style="min-width: 5rem" class="align-top">Created</th>
                    <th class="align-top">Action</th>
                </tr>
            <tbody>
                @php $i=0; @endphp
                @foreach($coupons as $coupon)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{$coupon->name}}</td>
                    <td>{{$coupon->code}}</td>
                    <td>{{$coupon->type}}</td>
                    @if($coupon->type=='fixed')
                    <td><i class="fas fa-rupee-sign"></i> {{$coupon->coupon_value}}</td>
                    @else 
                    <td>{{$coupon->coupon_value}} <i class="fas fa-percent"></i></td>
                    @endif
                    <td>{{$coupon->expired_at}}</td>
                    <td>{{$coupon->created_at}}</td>

                    <td>
							@role('admin')
							<form method="POST" action="{{route('backend.coupons.destroy', $coupon->id)}}" style="display: inline-block;">
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