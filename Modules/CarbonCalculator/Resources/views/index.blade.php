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
		<a class="mb-2 mr-2 btn-icon-vertical btn btn-success" href="{{ route('add') }}">ADD</a>
		
		 <!--<a class="mb-2 mr-2 btn-icon-vertical btn btn-info" href="{{ route('carbon.projects.imp') }}"> 
                                Import 
           		   </a>-->
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
							@php 
                     $default_project=$user->default_project;
                      if(isset($project->id))
                      $default_project=$project->id;
                     @endphp
                               <div class="form-group"><h6>Projects</h6>
							   {{ Form::select('project_filter_id', $Projects, $default_project, [
                            'class' => "form-control multiselect-dropdown",
                            'id' => "project_filter",
                            'data-live-search'=>'true',
                            'onchange'=> 'changeProject(this.value)'
                        ]) }}
                                    
                                </div>
                            </div>
                        </div>
						<a class="mb-2 mr-2 btn-icon-vertical btn btn-dark"><span style="font-size:16px;color:#fff;">Total : {{ round($total,2) }} t Co<Sub>2</sub></sub> e</span> </a></br>
                         <table  class="table table-bordered table-hover" id="doc-datatable" data-table="project">
                            <thead>
                                	@foreach ($carbondatabase as $object)
		                        	@if($object->carbon_a_one_a_five_id =="1" || $object->user_database_id == "1")
                                <tr>
							<th width="5%">Date /Time</th>
                            <th width="5%">Materials/Type</th>
							<th width="5%">Transport</sub></th>
							<th width="5%">Wastage</th>
							<th width="5%">Quantity</th>
							<th width="5%">Total  t Co<Sub>2</sub></sub> e</th>
						    <th width="5%">Action</th>
						     <tfoot>
                           <tr>
                         <th colspan="5" style="text-align:right">Total:</th>
                        <th></th>
                       <th></th>
                       </tr>
                       </tfoot>
							</tr>
                                @elseif($object->ghg_id =="1")
                                 <tr>
							<th width="5%">Date /Time</th>
                            <th width="5%">Source</th>
							<th width="5%">Quantity</th>
							<th width="5%">Units</th>
							<th width="5%">Total  t Co<Sub>2</sub></sub> e</th>
						
						    <th width="5%">Action</th>
						     <tfoot>
                           <tr>
                         <th colspan="4" style="text-align:right">Total:</th>
                        <th></th>
                       <th></th>
                       </tr>
                       </tfoot>
							 </tr>
                                
                                	@else
                                	<tr>
                             <th width="5%">Date /Time</th>
                            <th width="5%">Source</th>
							<th width="5%">Quantity</th>
							<th width="5%">Total  t Co<Sub>2</sub></sub> e</th>
							<th width="5%">Action</th>
							<tfoot>
                           <tr>
                         <th colspan="3" style="text-align:right">Total:</th>
                        <th></th>
                       <th></th>
                       </tr>
                       </tfoot>
                                </tr>
                                	@endif
								     	@endforeach
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
	@foreach ($carbondatabase as $object)
		                        	@if($object->carbon_a_one_a_five_id =="1" || $object->user_database_id == "1")
<script> 
$(document).ready(function() {
    var table = $('#doc-datatable').DataTable({
        dom: 'Bfrtip',
        paging: true,
        autoWidth: false,
        responsive: true,
        processing: true,
        serverSide: true,
        searchable: true,
        pageLength: 25,
        order: [[0, 'asc']],
        ajax: {
            url: "{{ route('carboncalculator.ajax.list.all') }}",
            type: 'GET',
            data: function(d) {
                d.project_filter_id = $('#project_filter').val();
            }
        },
        columns: [
            {data: 'created_at', name: 'created_at'},
            {data: 'materials', name: 'materials'},
            {data: 'transport', name: 'transport'},
            {data: 'wastage', name: 'wastage'},
            {data: 'quantity', name: 'quantity'},
            {data: 'total', name: 'total'},
            {data: 'action', name: 'action'}
        ],
        deferRender: true,
        columnDefs: [{
            targets: 'no-sort',
            orderable: false,
        }],
        footerCallback: function(row, data, start, end, display) {
            var api = this.api(), data;
            var total = api.column(5, {page: 'current'}).data().reduce(function(a, b) {
                return parseFloat(a) + parseFloat(b);
            }, 0);
            
            $(api.column(5).footer()).html(total.toFixed(2));
        }
    });

    $('#project_filter').on('change', function() {
        table.draw();
    });
});

    </script>
     @elseif($object->ghg_id =="1")
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
						 //d.search = $('input[type="search"]').val()
                    }
                },
                columns: [
                     {data: 'created_at', name: 'created_at'},
                    {data: 'materials', name: 'materials'},
                    //{data: 'transport', name: 'transport'},
                    //{data: 'wastage', name: 'wastage'},
                    //{data: '', name: ''},
                    {data: 'quantity', name: 'quantity'},
                     {data: 'unit', name: 'unit'},
                    {data: 'total', name: 'total'},
                    
                   
                 {data: 'action', name: 'action'}
                  
                ],
                "deferRender": true,
                'columnDefs': [{
                    "targets": 'no-sort',
                    "orderable": false,
                }],
        footerCallback: function(row, data, start, end, display) {
            var api = this.api(), data;
            var total = api.column(4, {page: 'current'}).data().reduce(function(a, b) {
                return parseFloat(a) + parseFloat(b);
            }, 0);
            
            $(api.column(4).footer()).html(total.toFixed(2));
        }
            });
            $('#project_filter').on('change', function() {
                $('#doc-datatable').DataTable().draw(true);
            });
        });
    </script>
 	@else
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
						 //d.search = $('input[type="search"]').val()
                    }
                },
                columns: [
                    
                     {data: 'created_at', name: 'created_at'},
                    {data: 'materials', name: 'materials'},
                    //{data: 'transport', name: 'transport'},
                    //{data: 'wastage', name: 'wastage'},
                    //{data: '', name: ''},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'total', name: 'total'},
                   
                 {data: 'action', name: 'action'}
                  
                ],
                "deferRender": true,
                'columnDefs': [{
                    "targets": 'no-sort',
                    "orderable": false,
                }],
                footerCallback: function(row, data, start, end, display) {
            var api = this.api(), data;
            var total = api.column(3, {page: 'current'}).data().reduce(function(a, b) {
                return parseFloat(a) + parseFloat(b);
            }, 0);
            
            $(api.column(3).footer()).html(total.toFixed(2));
        }
            });
            $('#project_filter').on('change', function() {
                $('#doc-datatable').DataTable().draw(true);
            });
        });
    </script>
    	@endif
		@endforeach
@endpush