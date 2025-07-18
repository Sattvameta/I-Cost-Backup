@extends('user::layouts.master')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Central Doc Manager</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{route('dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a>
                        </li>                 
                        <li class="breadcrumb-item active">Doc</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
	<section class="content">
        @include('layouts.flash.alert')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
								{{ Form::label('project_filter_id', 'Project') }}
                                    {{ Form::select('project_filter_id', $projects, auth()->user()->default_project, [
                                    'class' => "form-control multiselect-dropdown",
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
			console.log("In the beginning of DocumentManagerController of AjaxListAllDocument");
            var t = $('#document-datatable').DataTable({
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
                    url: "{{ route('documentmanager.ajax.list.all') }}",
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
                    {data: 'value', name: 'value'}
                ],
                "deferRender": true,
                'columnDefs': [{
                    "targets": 'no-sort',
                    "orderable": false,
                }]
            });
            $('#project_filter').on('change', function() {
                $('#document-datatable').DataTable().draw(true);
            });
        });
    </script>

@endpush
