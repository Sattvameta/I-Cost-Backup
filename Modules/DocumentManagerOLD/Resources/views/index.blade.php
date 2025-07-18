@extends('user::layouts.masterlist')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Central Doc Manager</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a>
                        </li>                 
                        <li class="breadcrumb-item active">Central Doc Manager</li>
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
						<div class="col-md-9">
                                <div class="form-group">
								<label>Search</label><br>
						   <input id="myInput" type="text">
                            </a>
						  </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {{ Form::label('project_filter_id', 'Project') }}
                                    {{ Form::select('project_filter_id', $allProjects, '', [
                                    'class' => "form-control select2-input",
                                    'id' => "project_filter",
                                    'data-live-search'=>'true',
                                    ]) }}
                                </div>
                            </div>
							
                        </div>
                        <table  class="table table-bordered table-hover" id="doc-datatable" data-table="project">
                            <thead>
                                <tr>
                                    <th class="no-sort">Doc Ref</th>
                                    <th>Doc Title</th>
                                    <th>Module</th>
                                    <th>Description</th>
                                    <!--<th>Uploaded By</th>-->
                                    <th>Doc Type</th>
                                    <th>Blockchain</th>
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
            var t = $('#doc-datatable').DataTable({
                "processing": true,
			  "serverSide": true,
              "searching": false,
                ajax: {
                    //url: "{{ route('formulas.ajax.list.all') }}",
					url: "{{ route('documentmanager.ajax.list.all') }}",
                    type: 'GET',
                    data: function(d) {
						
                        //d.status_filter_id = $('#status_filter').val();
                        d.project_filter_id = $('#project_filter').val();
                    }
                },
                columns: [
                    
                    {data: 'id', name: 'id'},
                    {data: 'cer_or_delnote', name: 'cer_or_delnote'},
                    {data: 'module', name: 'module'},
                    {data: 'category', name: 'category'},
                    //{data: '', name: ''},
                    {data: 'doc_type', name: 'doc_type'},
                    {data: 'check', name: 'check'},
                    {data: 'action', name: 'action'}
                ],
                "deferRender": true,
                'columnDefs': [{
                    "targets": 'no-sort',
                    "orderable": false,
                }]
            });
            $('#project_filter').on('change', function() {
                $('#doc-datatable').DataTable().draw(true);
            });
        });
    </script>
	<script>
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#doc-datatable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>
@endpush