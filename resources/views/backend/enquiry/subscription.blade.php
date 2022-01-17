@extends('backend.layouts.layout-2')
@section('scripts')

<script type="text/javascript">
       $(document).ready(function () {

	$('#ResetBtn').on('click', function () {
            window.location.href = "{{route('backend.newsletters')}}";

        });
	
	
    });
</script>
@endsection
@section('content')
@includeif('backend.message')
	

<h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-4">
    <div>Newsletters</div>
</h4>

<div class="card">

    <div class="card-datatable table-responsive">
        <table id="video-list" class="table table-striped table-bordered">
            <thead>
                <tr>
				<form action="" id="filterForm"  action="{{route('backend.newsletters')}}" method = "get"  >
				
                  
                    <th class="align-top">
                        Email
                        <input type="text" value="{{request('emailinput')}}" name="emailinput" class="form-control">
                    </th>
                  
                    <th class="align-top">Action
					<div class="row">
					<input type="submit" value="Filter" style="margin-left: 6px; margin-right: 7px;" id="filterBtn" class="btn btn-primary rounded-pill d-block btn-sm">
					<input type="button" value="Reset" id="ResetBtn" class="btn btn-primary rounded-pill d-block btn-sm">
					</div>
					</th>
					</form>
                </tr>
            <tbody>
                @php $i=0; @endphp
                @foreach($newsletters as $newsletter)
                <tr>
                    <td>{{$newsletter->email}}</td>
                    <td>
                     
                        
                                         
                        <form method="POST" action="{{route('backend.destroyNewsletters', $newsletter->id)}}" style="display: inline-block;">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}

                            <button type="submit" onclick="return confirm('You are about to delete this record?')" class="btn btn-default btn-xs icon-btn md-btn-flat article-tooltip" title="Remove"><i class="ion ion-md-close"></i></button>
                        </form>
                        
                    </td>
                </tr>
                @endforeach
            </tbody>
            </thead>
        </table>
		{{ $newsletters->appends(request()->input())->links() }}
    </div>
</div>
@endsection
