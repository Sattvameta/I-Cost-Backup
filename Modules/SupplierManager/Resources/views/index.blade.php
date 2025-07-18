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
                        <li class="breadcrumb-item active">Suppliers</li>
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
                
                <div class="card-body">

                        <h5 class="card-title">Suppliers</h5>
                      @if (auth()->user()->can('access', 'suppliers add') && (auth()->user()->isRole('Super Admin') || auth()->user()->isRole('Admin')))

                        
                        <button onclick="window.location.href='{{ route('suppliers.import') }}'"  type="button" class="mb-2 mr-2 btn-icon-vertical btn btn-primary">
                        <i class="pe-7s-cloud-upload btn-icon-wrapper"></i>Import Supplier
                        </button>
                     
                       
                        <button  onclick="window.location.href='{{ route('suppliers.create') }}'"  type="button" class="mb-2 mr-2 btn-icon-vertical btn btn-info">
                        <i class="pe-7s-add-user btn-icon-wrapper"></i>Create Supplier
                        </button>


                        @endif
                    </div>
                <div class="card">
                  
                    <div class="card-body">
                        <div class="row">
						<div class="col-md-3">
                                <div class="form-group">
								<label>Search</label><br>
						   <input id="myInput" type="text">
						  </div>
                            </div>
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
                                    {{ Form::label('category_filter', 'Category') }}
                                    {{ Form::select('category_filter', $categories, '', [
                                        'class' => "multiselect-dropdown form-control category_filter",
                                        'id' => "category_filter",
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
                        </div>
                        <div class="table-responsive">
                        <table  class="table table-hover table-striped table-bordered " id="suppliers-datatable" data-table="suppliers-datatable">
                            <thead>
                                <tr>
                                    <th >Company</th>
                                    <th>Name</th>                           
                                    <th>Email</th>
                                    <th>Phone</th>
                                     <th>Insurance Details</th>
                                    <th  class="no-sort">Status</th>
                                    <th class="no-sort">Actions</th>
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
            var t = $('#suppliers-datatable').DataTable({
                 "processing": true,
			  "serverSide": true,
              "searching": false,
                ajax: {
                    url: "{{ route('suppliers.ajax.list.all') }}",
                    type: 'GET',
                    data: function(d) {
                        d.status_filter = $('#status_filter').val();
                        d.category_filter = $('#category_filter').val();
                        d.company_filter = $('#company_filter').val();
                    }
                },
                columns: [                                      
                    {data: 'company_id', name: 'company_id'},
                    {data: 'supplier_name', name: 'supplier_name'},
                    {data: 'email', name: 'email'},
                    {data: 'phone', name: 'phone'},
                    {data: 'avatar', name: 'avatar'}, 
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action'}
                ],
                "deferRender": true,
                'columnDefs': [{
                        "targets": 'no-sort',
                        "orderable": false,
                    }]
            });
            $('#status_filter, #category_filter, #company_filter').on('change', function() {
                $('#suppliers-datatable').DataTable().draw(true);
            });
        });
    </script>
	<script>
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#suppliers-datatable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>
@endpush