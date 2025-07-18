@extends('user::layouts.masterlist')
@section('content')
<!DOCTYPE html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base_url" content="{{ URL::to('/') }}">
</head>
<body>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Cost Manager</h1>
                </div>
                <div class="col-sm-6" style="flex: 0 0 15%;">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a>
                        </li>                 
                        <li class="breadcrumb-item active">Cost</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        @include('layouts.flash.alert')
        <!--<div class="row">
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('company_id')) has-error @endif">
                        {{ Form::label('company_id', 'Company') }}<span class="asterisk"></span>
                       <input type="number" id="size" name="size" min="1" >
<input type="text" id="size_unit" name="size_unit"  >
                        @if($errors->has('company_id'))
                            <span class="invalid-feedback">{{ $errors->first('company_id') }}</span>
                        @endif
                    </div>
                </div>
                
        </div>-->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title"></h3>
                       
                     
                            <!--<table  class="table table-bordered table-hover" id="users-datatable" >-->
                                <table id="users-datatable" class="table table-hover table-striped table-bordered table-responsive" cellspacing="0" width="100%"  data-table="users-datatable">
                                <thead>
                                    <tr>
                                      <th colspan="11"><center>Project</center></th>
                                      <th colspan="6"><center>Estimate</center></th>
                                 
                                    </tr>
                                    <tr>		
                                        <th>Id</th>
                                        <th class="no-sort">Reference No</th>
                                        <th>Title</th>
                                        <th>Company</th>
                                        <th class="no-sort">Size</th>
                                        <th class="no-sort">Region</th>
                                        <th  class="project-state no-sort">Contract Type</th>
                                        <th class="project-actions no-sort">Shifts</th>
                                        <th class="project-actions no-sort">Tender Status</th>
                                        <th class="project-actions no-sort">Bid Value</th>
                                        <th class="project-actions no-sort">Margin</th>
                                        <th class="project-actions no-sort">Estimate</th>
                                        <th class="project-actions no-sort">Actuals</th>
                                        <th class="project-actions no-sort">Status</th>
                                        <th class="project-actions no-sort">Start date</th>
                                        <th class="project-actions no-sort">End date</th>
                                        <th class="project-actions no-sort">Actual End date</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                    
                                    @foreach($projects as $value)
                                   
                                    <tr>
                                        
                                        <td >{{$value['id']}}</td>
                                        <td >{{$value['unique_reference_no']}}</td>
                                        <td >{{$value['project_title']}}</td>
                                        <td >{{$value['company_name']}}</td>
                                        <td ></td>
                                        <td >{{$value['location']}}</td>
                                        <td >{{$value['type_of_contract']}}</td>
                                        <td >{{$value['shifts']}}</td>
                                        <td >{{$value['tender_status']}}</td>
                                        <td ></td>
                                        <td >{{$value['base_margin']}}</td>
                                       
                                        <td > @isset($value['estimate'])&pound;{{ number_format($value['estimate'], 2) }}@endisset</td>
                                        
                                        
                                        <td >@isset($value['Actuals'])&pound;{{ number_format($value['Actuals'], 2) }} @else &pound;{{ number_format(0, 2) }} @endisset</td>
                                        <td >@if($value['status'] ==1)
                                              Active
                                                @elseif($value['status'] ==0)
                                                Inactive
                                                @endif</td>
                                        <td >{{$value['current_start_date']}}</td>
                                        <td >{{$value['current_completion_date']}}</td>
                                        <td></td>
                                    </tr>
                                    @endforeach   
                                </tbody>
                            </table>
                    </div>
                    <div class="card-body-1" id="test">
                    </div>
                </div>
            </div>
        </div>
    </section>
</body><br><br>
@stop
<script src="plugins/jquery/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
    $('#users-datatable').DataTable();
    $('.dataTables_length').addClass('bs-select');
    });
</script>
