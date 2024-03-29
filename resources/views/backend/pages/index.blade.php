@extends('backend.layouts.layout-2')

@section('scripts')

<script type="text/javascript">
    $(function () {
        $('#course-list').dataTable(
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
    <div>Pages</div>
</h4>

<div class="card">
    <div class="card-datatable table-responsive">
        <table id="course-list" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="align-top">S.No</th>
                    <th style="min-width: 18rem" class="align-top">Title</th>
                    <th style="min-width: 18rem" class="align-top">Content</th>
                    <th class="align-top">Action</th>
                </tr>
            <tbody>
                @php $i=0; @endphp
                @foreach($pages as $page)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{$page->title}}</td>
                    <td>{{$page->page_content}}</td>
                    <td>
							<a href ="{{route('backend.pages.edit', $page->id)}}" class="btn btn-default btn-xs icon-btn md-btn-flat article-tooltip" title="Edit"><i class="ion ion-md-create"></i></a>
							
                    </td>
                </tr>
                @endforeach
            </tbody>
            </thead>
        </table>
    </div>
</div>
@endsection