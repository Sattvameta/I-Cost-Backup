@extends('user::layouts.masterlist')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Manage Formulas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a>
                        </li>                 
                        <li class="breadcrumb-item active">Formulas</li>
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
                        <div class="row">
                            <!--<div class="col-md-2">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Formula Status</label>
                                    <select name="status_filter_id" class="form-control select2-input" id="status_filter">
                                        <option value="">All</option>
                                        <option value="1">Active</option>
                                        <option value="0">InActive</option>
                                    </select>
                                </div>
                            </div>-->
                            <div class="col-md-3">
                                <div class="form-group">
                                    {{ Form::label('project_filter_id', 'Project') }}
                                    {{ Form::select('project_filter_id', $projects, '', [
                                    'class' => "form-control select2-input",
                                    'id' => "project_filter",
                                    'data-live-search'=>'true',
                                    ]) }}
                                </div>
                            </div>
                        </div>
                        <table  class="table table-bordered table-hover" id="formula-datatable" data-table="projects">
                            <thead>
                                <tr>
                                    <th class="no-sort">Project</th>
                                    <th>Keyword OR Unit</th>
                                    <th>Description</th>
                                    <th>Formula</th>
                                    <th>Value</th>
                                    <th class="project-actions no-sort">Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var t = $('#formula-datatable').DataTable({
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
                    url: "{{ route('formulas.ajax.list.all') }}",
                    type: 'GET',
                    data: function(d) {
                        //d.status_filter_id = $('#status_filter').val();
                        d.project_filter_id = $('#project_filter').val();
                    }
                },
                columns: [
                    {data: 'project_title', name: 'project_title'},
                    {data: 'keyword', name: 'keyword'},
                    {data: 'description', name: 'description'},
                    {data: 'formula', name: 'formula'},
                    {data: 'value', name: 'value'},
                    {data: 'action', name: 'action'}
                ],
                "deferRender": true,
                'columnDefs': [{
                    "targets": 'no-sort',
                    "orderable": false,
                }]
            });
            $('#status_filter,#project_filter').on('change', function() {
                $('#formula-datatable').DataTable().draw(true);
            });
        });
    </script>
@endpush