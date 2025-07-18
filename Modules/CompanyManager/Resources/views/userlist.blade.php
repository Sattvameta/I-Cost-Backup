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
                    <li class="breadcrumb-item"><a href="{{ route('companies.index') }}"><i class="fa fa-dashboard"></i> Companies</a></li>
                    <li class="breadcrumb-item active">User List</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
     <div class="card-body">                    
		<button onclick="window.location.href='{{ route('companies.index') }}'"  type="button" class="mb-2 mr-2 btn-icon-vertical btn btn-info">
		<i class="pe-7s-back btn-icon-wrapper"></i>Back
		</button>                       
     </div>
    <!-- Default box -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">User List</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th width="20%" class="table-active">Company Name : </th>
						<td>{{ $user->company_name }}</td>
                    </tr>
                    <tr>
					<th width="20%" class="table-active">User Name</th>
					<th width="20%" class="table-active">Email Id</th>
					<th width="20%" class="table-active">Roles</th>
                    </tr>
					@foreach($company as $com)
                    <tr>
							<td>{{ $com->full_name }}</td>			
							<td>{{ $com->email }}</td>
							<td>{{ $com->name }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div><br><br>
    <!-- /.card -->
</section>
@stop