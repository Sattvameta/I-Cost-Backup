@extends('user::layouts.masterlist')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{route('dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a>
                        </li>                 
                        <li class="breadcrumb-item active">Projects</li>
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
                    <div class="card-header">
                        <h3 class="card-title">Projects</h3><hr>
                        <div class="card-tools">
                            @if (auth()->user()->can('access', 'projects add'))
                              <!--  <a class="btn btn-primary btn-sm" href="{{ route('projects.new.version') }}">Create Project New Version</a>-->
                                <a class="btn btn-success btn-sm" href="{{ route('projects.add') }}">Create Project</a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
							 @if (auth()->user()->isRole('Super Admin') || auth()->user()->isRole('Admin'))
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Project Status</label>
                                    <select name="status_filter_id" class="form-control multiselect-dropdown" id="status_filter">
                                        <option value="">All</option>
                                        <option value="1">Active</option>
                                        <option value="0">InActive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tender Status</label>
                                    <select name="tender_status_filter_id" class="form-control multiselect-dropdown" id="tender_status_filter">
                                        <option value="">All</option>
                                        <option value="1">Live</option>
                                        <option value="2">Tender</option>
                                        <option value="0">Dead</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    {{ Form::label('region_filter_id', 'Region') }}
                                    {{ Form::select('region_filter_id',[''=>'All'] + config('constants.REGIONS'),'', [
                                    'class' => "form-control multiselect-dropdown",
                                    'id' => "region_filter",
                                    'data-live-search'=>'true',
                                    ]) }}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    {{ Form::label('shift_filter_id', 'Shifts') }}
                                    {{ Form::select('shift_filter_id',[''=>'All'] + config('constants.SHIFTS'),'', [
                                        'class' => "form-control multiselect-dropdown",
                                        'id' => "shift_filter",
                                        'data-live-search'=>'true',
                                    ]) }}
                                </div>
                            </div>
							 @endif
                        </div>
                         
							 @if (auth()->user()->isRole('Super Admin') || auth()->user()->isRole('Admin'))
                        <table  class="table-responsive table table-bordered table-hover" id="projects-datatable" data-table="projects">
                             <thead>
                                <tr>
                                    <th>Reference No</th>
                                    <th>Title</th>
                                    <th class="project-state no-sort">Company</th>
                                    <th>Region</th>
                                    <th>Contract Type</th>
                                    <th>Shifts</th>
                                    <th  class="project-state no-sort">Tender Status</th>
                                    <th  class="project-state no-sort">Bid Value</th>
                                    <th  class="project-state no-sort">Status</th>
                                    <th class="project-actions no-sort">Actions</th>
                                </tr>
								
                            </thead>
                        </table>
                          @endif      
						   @if(!auth()->user()->isRole('Admin')&&!auth()->user()->isRole('Super Admin'))
                           <table  class="table-responsive table table-bordered table-hover" id="projects-datatable" data-table="projects">
                             <thead>
                               
								 <tr>
                                    <th>Reference No</th>
                                    <th>Title</th>
                                    <th class="project-state no-sort">Company</th>
                                    <th>Region</th>
                                   
                                    <th  class="project-state no-sort">Bid Value</th>
                                   
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
            var t = $('#projects-datatable').DataTable({
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
                    url: "{{ route('project.ajax.list.all') }}",
                    type: 'GET',
                    data: function(d) {
                        d.status_filter_id = $('#status_filter').val();
                        d.tender_status_filter_id = $('#tender_status_filter').val();
                        d.region_filter_id = $('#region_filter').val();
                        d.shift_filter_id = $('#shift_filter').val();
                    }
                },
                columns: [
                    {data: 'unique_reference_no', name: 'unique_reference_no'},
                    {data: 'project_title', name: 'project_title'},
                    {data: 'company', name: 'company_id'},
                    {data: 'region', name: 'region'},
                    {data: 'type_of_contract', name: 'type_of_contract'},
                    {data: 'shifts', name: 'shifts'},
                    {data: 'tender_status', name: 'tender_status'},
                    {data: 'bid_value', name: 'bid_value'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action'}
                ],
                "deferRender": true,
                'columnDefs': [{
                    "targets": 'no-sort',
                    "orderable": false,
                }]
            });
            $('#status_filter,#tender_status_filter,#region_filter,#shift_filter').on('change', function() {
                $('#projects-datatable').DataTable().draw(true);
            });
        });
    </script>
	@endif
	  @if(!auth()->user()->isRole('Admin')&&!auth()->user()->isRole('Super Admin'))
	<script type="text/javascript">
        $(document).ready(function() {
            var t = $('#projects-datatable').DataTable({
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
                    url: "{{ route('project.ajax.list.all') }}",
                    type: 'GET',
                    data: function(d) {
                        d.status_filter_id = $('#status_filter').val();
                        d.tender_status_filter_id = $('#tender_status_filter').val();
                        d.region_filter_id = $('#region_filter').val();
                        d.shift_filter_id = $('#shift_filter').val();
                    }
                },
                columns: [
                    {data: 'unique_reference_no', name: 'unique_reference_no'},
                    {data: 'project_title', name: 'project_title'},
                    {data: 'company', name: 'company_id'},
                    {data: 'region', name: 'region'},
                  
                    {data: 'bid_value', name: 'bid_value'},
                   
                    {data: 'action', name: 'action'}
                ],
                "deferRender": true,
                'columnDefs': [{
                    "targets": 'no-sort',
                    "orderable": false,
                }]
            });
            $('#status_filter,#tender_status_filter,#region_filter,#shift_filter').on('change', function() {
                $('#projects-datatable').DataTable().draw(true);
            });
        });
    </script>
	@endif
@endpush