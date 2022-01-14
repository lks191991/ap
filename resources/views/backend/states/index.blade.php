@extends('backend.layouts.layout-2')

@section('scripts')

<script type="text/javascript">
    $(function () {
        $('#category-list').dataTable(
			{
            "order": [[ 0, "asc" ]],
            "columns": [
              null,
              null,
			  null,
             { "orderable": false }
            ]
          }
		);
    });
</script>
@endsection

@section('content')

@includeif('backend.message')

<h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-4">
    <div>States</div>
    <a href="{{route('backend.states.create')}}" class="btn btn-primary rounded-pill d-block"><span class="ion ion-md-add"></span>&nbsp;Create State</a>
</h4>

<div class="card">
    <div class="card-datatable table-responsive">
        <table id="category-list" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th style="min-width: 18rem">State Name</th>
					<th>Status</th>
                    <th class="align-top">Action</th>
                </tr>
            <tbody>
                @php $i=0; @endphp
                @foreach($states as $state)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{$state->name}}</td>
					<td>{{$state->status ? 'Active':'Disabled'}}</td>
                    <td>
                        <a href ="{{route('backend.states.edit', $state->id)}}" class="btn btn-default btn-xs icon-btn md-btn-flat article-tooltip" title="Edit"><i class="ion ion-md-create"></i></a>
                        @role('admin')
						<form method="POST" action="{{route('backend.states.destroy', $state->id)}}" style="display: inline-block;">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}

                            <button type="submit" onclick="return confirm('You are about to delete this record? If the state is deleted, then the dependent zones,districts,cities,colleges will also be deleted.')" class="btn btn-default btn-xs icon-btn md-btn-flat article-tooltip" title="Remove"><i class="ion ion-md-close"></i></button>

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