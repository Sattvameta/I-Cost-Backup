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
                    <li class="breadcrumb-item active">Edit Project</li>
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
				  
					@foreach($project->unique('project_id') as $pro)
						 <tr>
						 <td>
						<input type="hidden" id="id" name="id" value="{{$pro->id}}">
						<a title="Delete Project" onclick="return confirm('Are you sure want to remove the Project?')" href="../../users/updateproject/{{$pro->id}}"  class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>
						<!--<input type="checkbox" id="checkbox" name="checkbox" value="{{$pro->id}}" checked>-->
						</td>
						 <td>{{ $pro->project_title }} (V-{{$pro->version}})</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
		<div class="card-footer" style="margin-left: 925px;">
            
        </div>
		
    </div><br>
	
    <!-- /.card -->
</section>
@stop