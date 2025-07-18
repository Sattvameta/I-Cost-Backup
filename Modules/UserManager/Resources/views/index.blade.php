@extends('user::layouts.masterlist')
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
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a>
                        </li>                 
                        <li class="breadcrumb-item active">Users</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        @include('layouts.flash.alert')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    
                    
                    
                        <div class="card-body">

                        <h5 class="card-title">Users</h5>
                        @if (auth()->user()->isRole('Super Admin'))
                        
                        <button onclick="window.location.href='{{ route('users.admins') }}'" class="mb-2 mr-2 btn-icon-vertical btn btn-primary">
                        <i class="pe-7s-settings btn-icon-wrapper"></i>View Admin
                        </button>
                      


                        @endif

                        @if (auth()->user()->can('access', 'users add') && (auth()->user()->isRole('Super Admin') || auth()->user()->isRole('Admin')))
                       
                        <button onclick="window.location.href='{{ route('users.create') }}'" class="mb-2 mr-2 btn-icon-vertical btn btn-info">
                        <i class="pe-7s-add-user btn-icon-wrapper"></i>Create User
                        </button>

                        @endif
                        </div>
                    
                    <div class="card-body table-responsive" >
                        
                      
                        <div class="row">
						
							 @if (auth()->user()->isRole('Super Admin') || auth()->user()->isRole('Admin'))
                            <div class="col-md-3">
                                <div class="form-group">
                                    {{ Form::label('status_filter', 'Status') }}
                                    {{ Form::select('status_filter', [''=> 'All', 1=> 'Active', 0=> 'In-active'], '', [
                                        'class' => "multiselect-dropdown form-control status_filter",
                                        'id' => "status_filter",
                                        'data-live-search'=>'true',
                                    ]) }}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {{ Form::label('role_filter', 'Role') }}
                                    {{ Form::select('role_filter', $roles, '', [
                                        'class' => "multiselect-dropdown form-control role_filter",
                                        'id' => "role_filter",
                                        'data-live-search'=>'true',
                                    ]) }}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {{ Form::label('company_filter', 'Company') }}
                                    {{ Form::select('company_filter',  $companies, '', [
                                        'class' => "multiselect-dropdown form-control company_filter",
                                        'id' => "company_filter",
                                    ]) }}
                                </div>
                            </div>
							  @endif
                        </div>
						 @if (auth()->user()->isRole('Super Admin') || auth()->user()->isRole('Admin'))
                        <table style="width: 80%;" id="users-datatable" data-table="users-datatable" class="table table-hover table-striped table-bordered table-responsive">
                      
                            <thead>
							
                                <tr>
                                    <th class="no-sort">Avatar</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th class="no-sort">Company</th>
                                    <th class="no-sort">Role</th>
                                    <th  class="project-state no-sort">Status</th>
                                    <th class="project-actions no-sort">Actions</th>
                                </tr>
								
								
                            </thead>
                        </table>
						 @endif
						 @if(!auth()->user()->isRole('Admin')&&!auth()->user()->isRole('Super Admin'))
							 
						   <table style="width: 80%;" id="users-data" data-table="users-datatable" class="table table-hover table-striped table-bordered table-responsive">
						   <thead>
						  <tr>
                                    <th width="5%"class="no-sort">Avatar</th>
                                    <th >Name</th>
                                    <th>Email</th>
                                    <th class="project-actions no-sort">Actions</th>
                                </tr>
								</thead>
                        </table>
						 @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
@push('scripts')
 @if (auth()->user()->isRole('Super Admin') || auth()->user()->isRole('Admin'))
    <script type="text/javascript">
        $(document).ready(function() {
            var t = $('#users-datatable').DataTable({
              				 dom: 'Bfrtip',
             "paging": true,
                "autoWidth": false,
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "searchable": true,
                "pageLength": 25,
                "order": [[0, 'asc']],
                ajax: {
                    url: "{{ route('users.ajax.list.all') }}",
                    type: 'GET',
                    data: function(d) {
                        d.status_filter = $('#status_filter').val();
                        d.role_filter = $('#role_filter').val();
                        d.company_filter = $('#company_filter').val();
                    }
                },
                columns: [
                    {data: 'avatar', name: 'avatar'},
                    {data: 'full_name', name: 'full_name'},
                    {data: 'email', name: 'email'},
                    {data: 'company', name: 'company'},
                    {data: 'role', name: 'role'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action'}
                ],
                "deferRender": true,
                'columnDefs': [{
                        "targets": 'no-sort',
                        "orderable": false,
                    }]
            });
            $('#status_filter, #role_filter, #company_filter').on('change', function() {
                $('#users-datatable').DataTable().draw(true);
            });
        });
    </script>
	 @endif
	 @if(!auth()->user()->isRole('Admin')&&!auth()->user()->isRole('Super Admin'))
	  <script type="text/javascript">
        $(document).ready(function() {
            var t = $('#users-data').DataTable({
              				 dom: 'Bfrtip',
             "paging": true,
                "autoWidth": false,
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "searchable": true,
                "pageLength": 25,
                "order": [[0, 'asc']],
                ajax: {
                    url: "{{ route('users.ajax.list.all') }}",
                    type: 'GET',
                    data: function(d) {
                        d.status_filter = $('#status_filter').val();
                        d.role_filter = $('#role_filter').val();
                        d.company_filter = $('#company_filter').val();
                    }
                },
                columns: [
                    {data: 'avatar', name: 'avatar'},
                    {data: 'full_name', name: 'full_name'},
                    {data: 'email', name: 'email'},
                  //  {data: 'company', name: 'company'},
                    //{data: 'role', name: 'role'},
                   // {data: 'status', name: 'status'},
                    {data: 'action', name: 'action'}
                ],
                "deferRender": true,
                'columnDefs': [{
                        "targets": 'no-sort',
                        "orderable": false,
                    }]
            });
            $('#status_filter, #role_filter, #company_filter').on('change', function() {
                $('#users-data').DataTable().draw(true);
            });
        });
    </script>
	 @endif
@endpush