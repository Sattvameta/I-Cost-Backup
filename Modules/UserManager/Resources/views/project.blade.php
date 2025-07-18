@extends('user::layouts.master')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}"><i class="fa fa-dashboard"></i> Users</a></li>
                    <li class="breadcrumb-item active">Add Project</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    
    
      
       <div class="card-body">

<button onclick="window.location.href='{{ route('users.index') }}'"  type="button" class="mb-2 mr-2 btn-icon-vertical btn btn-info">
<i class="pe-7s-back btn-icon-wrapper"></i>Back
</button>     
         
     </div>
	
    <!-- Default box -->
    <div class="card">
	 {{ Form::open(['route' => ['users.storeproject'], 'method' => 'post', 'enctype'=> 'multipart/form-data']) }}
	 
        <div class="card-header">
            <h3 class="card-title">Project</h3>
            
        </div>
		 
		
        <div class="card-body">
		
					 
						
            <div class="table-responsive">
			 
			          
						
						
                <table class="table table-bordered">
				<tr>
				<th width="20%" class="table-active"></th>
				<th width="20%" class="table-active">Projects</th>
				</tr>
				 
					@foreach($project as $pro)
						 <tr>
						 <td>
						 <input type="hidden" id="users_id[]" name="users_id[]" value="{{$user->id}}">
						 <input type="checkbox" id="projects_status[]" name="projects_status[]" value="{{$pro->id}}">
						<!--{{ Form::label('', '') }}
                        {{ Form::checkbox('status', old('status'), [
                            'class' => "form-control status",
                            'id' => "status",
                        ]) }}-->
						</td>
						 <td>{{ $pro->project_title }}(V-{{$pro->version}})</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
		<div class="card-footer" style="margin-left: 925px;">
             {{ Form::submit('Save user', [ 'class' => "btn btn-primary btn-flat" ]) }}
        </div>
		{{ Form::close() }}
    </div><br>
	
    <!-- /.card -->
</section>
@stop