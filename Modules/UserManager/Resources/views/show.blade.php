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
                    <li class="breadcrumb-item active">User</li>
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
            <h3 class="card-title">User</h3>
            
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th width="20%" class="table-active">Name : </th>
                        <td>{{ $user->full_name }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">Company : </th>
                        <td>{{ $user->company->company_name }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">Email : </th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">Phone : </th>
                        <td>{{ $user->phone }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">Address line1 : </th>
                        <td>{{ $user->address_line1 }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">Address line2 : </th>
                        <td>{{ $user->address_line2 }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">Fax : </th>
                        <td>{{ $user->fax }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">Town : </th>
                        <td>{{ $user->suburb }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">Post code : </th>
                        <td>{{ $user->postcode }}</td>
                    </tr>
                    <tr>
                        <th class="table-active">Status : </th>
                        <td>
                            @if($user->status == 1)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">In-Active</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="table-active">Avatar : </th>
                        <td>
                            @if(\Storage::disk('public')->has($user->avatar))
                                <img src="{{ asset('storage/'.$user->avatar) }}" alt="" height="50px" width="50px">
                            @else
                                <img src="{{ asset('images/no-img-100x92.jpg') }}" alt="" height="50px" width="50px">
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="table-active">Member since : </th>
                        <td>{{ $user->created_at->format('d-m-Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <!-- /.card -->
</section>
@stop