@extends('backend.layouts.layout-2')

@section('content')
    <h4 class="font-weight-bold py-3 mb-4">
        <span class="text-muted font-weight-light">Pages /</span> Edit Page
    </h4>
	<div class="card mb-4">
        <h6 class="card-header">
            Edit Page
        </h6>
        <div class="card-body">
			@includeif('backend.message')
            <form action="{{route('backend.pages.update', $page->id)}}" method = "POST">
            @csrf
            @method('PUT')
            
			
                <div class="form-group row">
                    <label class="col-form-label col-sm-2 text-sm-right">Title</label>
                    <div class="col-sm-10">
                        <input type="text" name="title" placeholder="Title" class="form-control" value="{{$page->title}}" required>
                    </div>
                </div>
				
				
				
				<div class="form-group row">
                    <label class="col-form-label col-sm-2 text-sm-right">Page Content</label>
                    <div class="col-sm-10">
                        <textarea name="page_content" class="form-control" placeholder="Page Content">{{$page->page_content}}</textarea>
                    </div>
                </div>
				
				
                
                    <div class="col-sm-10 ml-sm-auto">
                        <a href = "{{route('backend.pages.index')}}" class="btn btn-danger mr-2">Cancel</a> <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection