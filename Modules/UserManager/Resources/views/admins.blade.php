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
                        <li class="breadcrumb-item active">Admins</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        @include('layouts.flash.alert')
        
         <div class="card-body">

     <a href="{{ route('users.index') }}"  type="button" class="mb-2 mr-2 btn-icon-vertical btn btn-info"><i class="pe-7s-back btn-icon-wrapper"></i>Back</a>     
         
     </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Admins</h3>
                        <!--<div class="card-tools">
                                <a class="btn btn-success btn-sm" href="{{ route('users.admins') }}">View Admins</a>
                            @if (auth()->user()->can('access', 'users add') && (auth()->user()->isRole('Super Admin') || auth()->user()->isRole('Admin')))
                                <a class="btn btn-success btn-sm" href="{{ route('users.create') }}">Create User</a>
                            @endif
                                
                        </div>-->
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    {{ Form::label('status_filter', 'Status') }}
                                    {{ Form::select('status_filter', [''=> 'All', 1=> 'Active', 0=> 'In-active'], '', [
                                        'class' => "form-control multiselect-dropdown status_filter",
                                        'id' => "status_filter",
                                        'data-live-search'=>'true',
                                    ]) }}
                                </div>
                            </div>
                           <!-- <div class="col-md-3">
                                <div class="form-group">
                                    {{ Form::label('role_filter', 'Role') }}
                                    {{ Form::select('role_filter', $roles, '', [
                                        'class' => "form-control multiselect-dropdown role_filter",
                                        'id' => "role_filter",
                                        'data-live-search'=>'true',
                                    ]) }}
                                </div>
                            </div>-->
                            <div class="col-md-3">
                                <div class="form-group">
                                    {{ Form::label('company_filter', 'Company') }}
                                    {{ Form::select('company_filter',  $companies, '', [
                                        'class' => "form-control multiselect-dropdown company_filter",
                                        'id' => "company_filter",
                                    ]) }}
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                        <table  class="table table-hover table-striped table-bordered" id="users-datatable" data-table="users-datatable">
                            <thead>
                                <tr>
                                    <th class="no-sort">Avatar</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th class="no-sort">Company</th>
                             
                                    <th  class="project-state no-sort">Status</th>
                                    <th class="project-actions no-sort">Actions</th>
                                </tr>
                            </thead>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var t = $('#users-datatable').DataTable({
                dom: 'Bfrtip',
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": true,                
                "processing": true,
                "serverSide": true,
                "searchable": true,
                "pageLength": 25,
                "order": [[0, 'asc']],
                ajax: {
                    url: "{{ route('users.ajax.list.admins') }}",
                    type: 'GET',
                    data: function(d) {
                        d.status_filter = $('#status_filter').val();
                        d.role_filter = $('#role_filter').val();
                        d.company_filter = $('#company_filter').val();
                    }
                },
                columns: [
                    {data: 'avatar', name: 'company_logo'},
                    {data: 'full_name', name: 'full_name'},
                    {data: 'email', name: 'email'},
                    {data: 'company', name: 'company'},
                   
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
@endpush