@extends('backend.layouts.layout-2')

@section('scripts')

<script type="text/javascript">
    $(document).ready(function () {

       
        var table = $('#city-list').DataTable({
            "order": [[0, "asc"]],
            "columns": [
                null,
                {"orderable": false},
                {"orderable": false},
                {"orderable": false},
                null,
                {"orderable": false},
				{"orderable": false}
            ],
            dom: 'lrtip'
        });

        $('#city_name').on('keyup', function () {
            regExSearch = this.value;
            table.column(1).search(regExSearch, true, false).draw();
            // table.search(this.value, true, false).draw();   
        });

      


    });
</script>
@endsection

@section('content')
@includeif('backend.message')

<h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-4">
    <div>Cities</div>
    <a href="{{route('backend.cities.create')}}" class="btn btn-primary rounded-pill d-block"><span class="ion ion-md-add"></span>&nbsp;Create City</a>
</h4>

<div class="card">

    <div class="card-datatable table-responsive">
        <table id="city-list" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="align-top">S.No</th>
                    <th style="min-width: 8rem" class="align-top">City Name<input type="text" name="city_name" id="city_name" class="form-control"></th>
                    <th style="min-width: 8rem" class="align-top">District</th>
					<th style="min-width: 8rem" class="align-top">Zone</th>
					<th style="min-width: 8rem" class="align-top">State</th>
                    <th class="align-top">Status</th>
                    <th class="align-top">Action</th>
                </tr>
            <tbody>
                @php $i=0; @endphp
                @foreach($cities as $city)

                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{$city->city_name}}</td>
                    <td>
                        @if(isset($city->district) && !empty($city->district->name))
                        {{$city->district->name}} 
                        @endif
                    </td>
                    <td>
                        @if(isset($city->zone) && !empty($city->zone->zone_name))
                        {{$city->zone->zone_name}} 
                        @endif
                    </td>
					<td>
                        @if(isset($city->state) && !empty($city->state->name))
                        {{$city->state->name }} 
                        @endif
                    </td>
                    <td>{{$city->status ? 'Active':'Disabled'}}</td>
                    <td>
                        <a href ="{{route('backend.cities.edit', $city->id)}}" class="btn btn-default btn-xs icon-btn md-btn-flat article-tooltip" title="Edit"><i class="ion ion-md-create"></i></a>
                        @role('admin')
                        <form method="POST" action="{{route('backend.cities.destroy', $city->id)}}" style="display: inline-block;">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}

                            <button type="submit" onclick="return confirm('You are about to delete this record? If the city is deleted, then the dependent colleges will also be deleted.')" class="btn btn-default btn-xs icon-btn md-btn-flat article-tooltip" title="Remove"><i class="ion ion-md-close"></i></button>

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