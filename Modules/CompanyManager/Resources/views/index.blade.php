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
                        <li class="breadcrumb-item active">Companies</li>
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
              @if (auth()->user()->can('access', 'admins add'))
                     
                      
                        <button onclick="window.location.href='{{ route('companies.create') }}'"  class="mb-2 mr-2 btn-icon-vertical btn btn-info">
                        <i class="pe-7s-add-user btn-icon-wrapper"></i>Create Company
                        </button>

                        
                        @endif
                    </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Companies</h3>
                       
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    {{ Form::label('category_filter', 'Category') }}
                                    {{ Form::select('category_filter', $categories, '', [
                                        'class' => "form-control multiselect-dropdown category_filter",
                                        'id' => "category_filter",
                                        'data-live-search'=>'true',
                                    ]) }}
                                </div>
                            </div>
                        </div>
                        
                     
                        <table  class="table table-hover table-striped table-bordered table-responsive" 
						id="companies-datatable" data-table="companies-datatable">
                            <thead>
                                <tr>
                                    <th class="no-sort">Logo</th>
                                    <th>Company Name</th>
                                    <th>Company Contact</th>
                                    <th>Admin Name</th>
                                    <th class="no-sort">Category</th>
                                    <th>Status</th>
                                    <th class="no-sort">Actions</th>
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
            var t = $('#companies-datatable').DataTable({
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
                    url: "{{ route('companies.ajax.list.all') }}",
                    type: 'GET',
                    data: function(d) {
                        d.status_filter = $('#status_filter').val();
                        d.category_filter = $('#category_filter').val();
                    }
                },
                columns: [
                    {data: 'logo', name: 'company_logo'},
                    {data: 'company_name', name: 'company_name'},
                    {data: 'company_contact', name: 'company_contact'},
                    {data: 'full_name', name: 'full_name'},
                    {data: 'category', name: 'category'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action'}
                ],
                "deferRender": true,
                'columnDefs': [{
                        "targets": 'no-sort',
                        "orderable": false,
                    }]
            });
            $('#status_filter, #category_filter').on('change', function() {
                $('#companies-datatable').DataTable().draw(true);
            });
        });
    </script>
	<script>
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#companies-datatable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>
@endpush