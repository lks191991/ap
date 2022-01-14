@extends('backend.layouts.layout-2')

@section('scripts')

<script type="text/javascript">
    $(function () {
        $('#districts-list').dataTable(
			{
            "order": [[ 0, "asc" ]],
            "columns": [
              null,
              null,
             { "orderable": false },
			  null,
              { "orderable": false }
            ],
			initComplete: function () {
            this.api().columns([2]).every( function () {
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
    <div>Districts</div>
    <a href="{{route('backend.districts.create')}}" class="btn btn-primary rounded-pill d-block"><span class="ion ion-md-add"></span>&nbsp;Create District</a>
</h4>

<div class="card">
    <div class="card-datatable table-responsive">
        <table id="districts-list" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="align-top">S.No</th>
                    <th style="min-width: 18rem" class="align-top">District Name</th>
                    <th style="min-width: 18rem" class="align-top">Zone Name</th>
					<th class="align-top">Status</th>
                    <th class="align-top">Action</th>
                </tr>
            <tbody>
                @php $i=0; @endphp
                @foreach($districts as $district)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{$district->name}}</td>
                    <td>{{$district->zone->zone_name}}</td>
					<td>{{$district->status ? 'Active':'Disabled'}}</td>
                    <td>
							<a href ="{{route('backend.districts.edit', $district->id)}}" class="btn btn-default btn-xs icon-btn md-btn-flat article-tooltip" title="Edit"><i class="ion ion-md-create"></i></a>
							@role('admin')
							<form method="POST" action="{{route('backend.districts.destroy', $district->id)}}" style="display: inline-block;">
								{{ csrf_field() }}
								{{ method_field('DELETE') }}

								<button type="submit" onclick="return confirm('You are about to delete this record? If the district is deleted, then the dependent cities,colleges will also be deleted.')" class="btn btn-default btn-xs icon-btn md-btn-flat article-tooltip" title="Remove"><i class="ion ion-md-close"></i></button>

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