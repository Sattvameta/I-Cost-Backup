@extends('user::layouts.masterlist')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
           
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{route('helpdesk')}}"><i class="fas fa-lock nav-icon"></i> Help Desk</a></li>                    
                    <!--<li class="breadcrumb-item active">Logo</li>-->
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>


<!-- Main content -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        @if(isset($_REQUEST['login']) && $_REQUEST['login'] =1 )
        <div class="alert alert-info">
            Please change your account password to continue using secure site.
        </div>
        @endif
        <section class="content" data-table="settings">
            <div class="row">



                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-body box box-default settings">
                          @include('layouts.flash.alert')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Query History</h3><hr>
                        <div class="card-tools">
                                
                            @if (!auth()->user()->isRole('Super Admin'))
                                <a class="btn btn-success btn-sm" href="{{ route('querycreate') }}">Launch Query</a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!--<div class="col-md-3">
                                <div class="form-group">
                                    {{ Form::label('status_filter', 'Status') }}
                                    {{ Form::select('status_filter', [''=> 'All', 1=> 'Active', 0=> 'In-active'], '', [
                                        'class' => "form-control multiselect-dropdown status_filter",
                                        'id' => "status_filter",
                                        'data-live-search'=>'true',
                                    ]) }}
                                </div>
                            </div>-->
							<div class="col-md-9">
                                <div class="form-group">
						      <label>Search</label><br>
						      <input id="myInput" type="text">
                             </a>
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
                        <table  class="table table-bordered table-hover" id="companies-datatable" data-table="companies-datatable">
                            <thead>
                                <tr>
                                  
                                    <th >Query Id</th>
                                    <th class="no-sort">Date</th>
                                   
                                    <th> Company </th>
                                    <th> Company User </th>
                                  
                                    <th >Query Type</th>
                                    <th>Status</th>
                                    <th class="text-right no-sort"> View </th>
                                       
                                   
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
                          
         
                          
                        </div>
                    </div>
                </div> 
           
        </section>
    </div>
  
   
</section>

@stop

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var t = $('#companies-datatable').DataTable({
                 "processing": true,
			  "serverSide": true,
              "searching": false,
                ajax: {
                    url: "{{ route('ajax.list.query') }}",
                    type: 'GET',
                    data: function(d) {
                    
                        d.category_filter = $('#category_filter').val();
                    }
                },
                columns: [
                    {data: 'query_id', name: 'query_id'},
                    {data: 'query_date', name: 'query_date'},
                    {data: 'company', name: 'company'},
                    {data: 'company_user', name: 'company_user'},
                    {data: 'query_type', name: 'query_type'},
                    {data: 'query_status', name: 'query_status'},
                    {data: 'action', name: 'action'},
                    
                ],
                "deferRender": true,
                'columnDefs': [{
                        "targets": 'no-sort',
                        "orderable": false,
                    }]
            });
            $('#category_filter').on('change', function() {
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
@section('per_page_style')
<style>
    .btn-file {
        position: relative;
        overflow: hidden;
    }
    .btn-file input[type=file] {
        position: absolute;
        top: 0;
        right: 0;
        min-width: 100%;
        min-height: 100%;
        font-size: 100px;
        text-align: right;
        filter: alpha(opacity=0);
        opacity: 0;
        outline: none;
        background: white;
        cursor: inherit;
        display: block;
    }

    #img-upload{
        width: 100%;
    }
</style>

@stop

