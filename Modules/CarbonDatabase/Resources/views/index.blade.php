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

						  {{ Form::open(['route' => ['store'], 'method' => 'post', 'enctype'=> 'multipart/form-data']) }}
					  <td><b style="color: #719370;">ICE DB V3.0 </b> <input type="checkbox" id="myCheck" onclick="myFunction()" name="ice" value="1"></td>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
					  <td><b style="color: #719370;">A1–A3 ECF (kgCO2e/kg)</b> <input type="checkbox" id="Check" onclick="myFunc()" name="ecf" value="1"></td>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
					  <td><b style="color: #719370;">Create(kgCO2e/kg)</b> <input type="checkbox" id="Cck" onclick="myFun()" name="cck" value="1"></td>
					  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
					  <td><b style="color: #719370;">GHG</b><input type="checkbox" id="ghg" onclick="my()" name="ghg" value="1"></td>
					  
					    {{ Form::submit('Save', [ 'class' => "btn btn-primary btn-flat" ]) }}
						 {{ Form::close() }}
						
                            <div id="text" style="display:none">
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
					  <div id="txt" style="display:none"><br>
						    <table  class="table table-bordered table-hover" id="carbon-datatable" data-table="project">
                            <thead style="background-color: #56d0ff;">
                                <tr>
                                    <th class="no-sort">S.No</th>
                                    <th>Materials</th>
                                    <th>Carbon -KgCo<sub>2</sub>/kg</th>
                                    <th>Mass</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
					<div id="texted" style="display:none"><br>
					   <a class="mb-2 mr-2 btn-icon-vertical btn btn-info" href="{{ route('carbon.proj.import') }}"> 
                                Import 
           		          </a>   
						    <table  class="table table-bordered table-hover" id="carbondatatable" data-table="project">
                            <thead style="background-color: #56d0ff;">
                                <tr>
                                    <th class="no-sort">S.No</th>
                                    <th>Materials</th>
                                    <th>Carbon -KgCo<sub>2</sub>/kg</th>
                                    <th>Mass</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    	<div id="ghgtexted" style="display:none"><br>
						    <table  class="table table-bordered table-hover" id="ghgtable" data-table="project">
                            <thead style="background-color: #56d0ff;">
                                <tr>
                                    <th class="no-sort">S.No</th>
                                    <th>Materials</th>
                                    <th>Carbon -KgCo<sub>2</sub>/kg</th>
                                    <th>Mass</th>
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
	 <script type="text/javascript">
        $(document).ready(function() {
            var t = $('#carbon-datatable').DataTable({
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
					url: "{{ route('carbon.ajax.list.all') }}",
                    type: 'GET',
                    data: function(d) {
						
                        //d.status_filter_id = $('#status_filter').val();
                        d.project_filter_id = $('#project_filter').val();
                    }
                },
                columns: [
                    
                    {data: 'id', name: 'id'},
                    {data: 'materials', name: 'materials'},
                    {data: 'factors', name: 'factors'},
                    {data: 'mass', name: 'mass'}
                    
                ],
                "deferRender": true,
                'columnDefs': [{
                    "targets": 'no-sort',
                    "orderable": false,
                }]
            });
            $('#project_filter').on('change', function() {
                $('#carbon-datatable').DataTable().draw(true);
            });
        });
    </script>
	 <script type="text/javascript">
        $(document).ready(function() {
            var t = $('#carbondatatable').DataTable({
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
					url: "{{ route('carbon.ajax.listed.all') }}",
                    type: 'GET',
                    data: function(d) {
						
                        //d.status_filter_id = $('#status_filter').val();
                        d.project_filter_id = $('#project_filter').val();
                    }
                },
                columns: [
                    
                    {data: 'id', name: 'id'},
                    {data: 'materials', name: 'materials'},
                    {data: 'factors', name: 'factors'},
                    {data: 'mass', name: 'mass'}
                    
                ],
                "deferRender": true,
                'columnDefs': [{
                    "targets": 'no-sort',
                    "orderable": false,
                }]
            });
            $('#project_filter').on('change', function() {
                $('#carbondatatable').DataTable().draw(true);
            });
        });
    </script>
     <script type="text/javascript">
        $(document).ready(function() {
            var t = $('#ghgtable').DataTable({
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
					url: "{{ route('carbon.ajax.ghg.all') }}",
                    type: 'GET',
                    data: function(d) {
						
                        //d.status_filter_id = $('#status_filter').val();
                        d.project_filter_id = $('#project_filter').val();
                    }
                },
                columns: [
                    
                    {data: 'id', name: 'id'},
                    {data: 'materials', name: 'materials'},
                    {data: 'factors', name: 'factors'},
                    {data: 'mass', name: 'mass'}
                    
                ],
                "deferRender": true,
                'columnDefs': [{
                    "targets": 'no-sort',
                    "orderable": false,
                }]
            });
            $('#project_filter').on('change', function() {
                $('#ghgtable').DataTable().draw(true);
            });
        });
    </script>
    <script>
    function myFunction() {
  var checkBox = document.getElementById("myCheck");
  var text = document.getElementById("text");
  if (checkBox.checked == true){
    text.style.display = "block";
  } else {
     text.style.display = "none";
  }
}
  </script>
  <script>
    function myFunc() {
  var checkBox = document.getElementById("Check");
  var txt = document.getElementById("txt");
  if (checkBox.checked == true){
    txt.style.display = "block";
  } else {
     txt.style.display = "none";
  }
}
  </script>
  <script>
    function myFun() {
  var checkBox = document.getElementById("Cck");
  var texted = document.getElementById("texted");
  if (checkBox.checked == true){
    texted.style.display = "block";
  } else {
     texted.style.display = "none";
  }
}
  </script>
   <script>
    function my() {
  var checkBox = document.getElementById("ghg");
  var ghgtexted = document.getElementById("ghgtexted");
  if (checkBox.checked == true){
    ghgtexted.style.display = "block";
  } else {
     ghgtexted.style.display = "none";
  }
}
  </script>
@endpush