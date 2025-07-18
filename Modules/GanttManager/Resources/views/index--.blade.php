@extends('user::layouts.masterlist')
@section('content')
<!DOCTYPE html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <script src="{{ asset('js/gant.js')}}"></script>

    <link href="{{ asset('css/gant.css')}}" rel="stylesheet">
  
   
    <style type="text/css">
       /* html, body{
           
            height:100%;
            padding:0px;
            margin:0px;
            overflow: hidden;
           
        }*/
        
#gantt_here{
    
    
    
    overflow: hidden;
    
}
        
    </style>
    
    
</head>
<body>
    
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Gantt Manager</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a>
                        </li>                 
                        <li class="breadcrumb-item active">Gantt</li>
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
                        <h3 class="card-title">Gantt</h3>
                        <!--<div class="card-tools">
                            @if (auth()->user()->can('access', 'admins add'))
                                <a class="btn btn-success btn-sm" href="{{ route('companies.create') }}">Create Company</a>
                            @endif
                        </div>-->
                    </div>
                     
                    <div class="card-body-1" id="test">
                        <div class="card-body ct-chart" id="gantt_here" style='width:100%; height:100%;'></div>
                    </div>
                </div>
           
            </div>
        </div>
    </section>

<script type="text/javascript">
gantt.config.date_format = "%Y-%m-%d %H:%i:%s";

gantt.init("gantt_here");

gantt.load("gantt/api/data");
</script>
</body>



@stop
