@extends('user::layouts.masterlist')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Embodied Carbon Factors</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a>
                        </li>                 
                        <li class="breadcrumb-item active">Carbon Database</li>
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
                            <div class="col-md-3">
                               <!-- <div class="form-group">
                                    {{ Form::label('project_filter_id', 'Project') }}
                                    {{ Form::select('project_filter_id', $allProjects, '', [
                                    'class' => "form-control select2-input",
                                    'id' => "project_filter",
                                    'data-live-search'=>'true',
                                    ]) }}
                                </div>-->
                            </div>
							
                        </div>
                        <table  class="table table-bordered table-hover" id="doc-datatable" data-table="project">
                            <thead style="background-color: #56d0ff;">
                                <tr>
                                    <th class="no-sort">S.No</th>
                                    <th>Materials</th>
                                    <th>Embodied Carbon -KgCo<sub>2</sub>/kg</th>
                                    <th>Notes</th>
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
              dom: 'Bfrtip',
             "paging": true,
                "autoWidth": false,
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "searchable": true,
                "pageLength": 15,
                "order": [[0, 'asc']],
                ajax: {
                    //url: "{{ route('formulas.ajax.list.all') }}",
					url: "{{ route('carbondatabase.ajax.list.all') }}",
                    type: 'GET',
                    data: function(d) {
						
                        //d.status_filter_id = $('#status_filter').val();
                        d.project_filter_id = $('#project_filter').val();
                    }
                },
                columns: [
                    
                    {data: 'id', name: 'id'},
                    {data: 'materials', name: 'materials'},
                    {data: 'embodied_carbon', name: 'embodied_carbon'},
                    {data: 'notes', name: 'notes'}
                    
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
@endpush