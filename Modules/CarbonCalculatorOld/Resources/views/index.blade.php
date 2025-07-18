@extends('user::layouts.masterlist')
@section('content')
<style>
.btn1 {
  background-color: DodgerBlue; /* Blue background */
  border: none; /* Remove borders */
  color: white; /* White text */
  padding: 8px 8px; /* Some padding */
  font-size: 16px; /* Set a font size */
  cursor: pointer; /* Mouse pointer on hover */
}

/* Darker background on mouse-over */
.btn1:hover {
  background-color: RoyalBlue;
}
</style>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>CO<sub>2</sub> Calculator</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a>
                        </li>                 
                        <li class="breadcrumb-item active">CO<sub>2</sub> Calculator</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
		<div class="col-sm-6"> 
		</div>
		<div class="col-sm-6">
		<a class="mb-2 mr-2 btn-icon-vertical btn btn-success" href="{{ route('add') }}"><i class="pe-7s-plus btn-icon-wrapper"></i>ADD</a>
		</div>
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
							<button>Total : {{ round($total,2) }} </button>
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
                            <thead>
                                <tr>
								 
                                   <th width="5%">Materials</th>
							<th width="5%">CO<sub>2</sub></th>
							<th width="5%">Quantity</th>
							<th width="5%">Total CO<sub>2</sub></th>
							<th width="5%">Action</th>
                                </tr>
                            </thead>
                        </table>
                     </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
	
						
@stop
@push('scripts')

<script> 

        $(document).ready(function() {
            var t = $('#doc-datatable').DataTable({
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
                    //url: "{{ route('formulas.ajax.list.all') }}",
					url: "{{ route('carboncalculator.ajax.list.all') }}",
                    type: 'GET',
                    data: function(d) {
						
                        //d.status_filter_id = $('#status_filter').val();
                        d.project_filter_id = $('#project_filter').val();
						 d.search = $('input[type="search"]').val()
                    }
                },
                columns: [
                    
                   // {data: 'id', name: 'id'},
                    {data: 'materials', name: 'materials'},
                    {data: 'embodied_carbon', name: 'embodied_carbon'},
                    {data: 'quantity', name: 'quantity'},
                    //{data: '', name: ''},
                    {data: 'total', name: 'total'},
                    {data: 'action', name: 'action'},
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