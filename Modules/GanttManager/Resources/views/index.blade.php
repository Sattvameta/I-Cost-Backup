@extends('user::layouts.masterlist')
@section('content')
<!DOCTYPE html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
     <meta name="base_url" content="{{ URL::to('/') }}">
    


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
            overflow-y: scroll;
            overflow: static;
            max-height: 450px;
        }
        
    </style>
</head>
<body>
    
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
             
                <div class="col-sm-12">
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
                        <h3 class="card-title">Gantt</h3><hr>
                        <div class=""> 
                            <a href="{{ route('gantt.projects.import.view') }}" class="btn btn-info btn-sm float-right">
                                Import Gantt
                            </a>
                            
                           <select class="form-control  col-md-2 float-right calen mr-2" id="select_cal">
                                <option value="day">Day</option>
                                <option value="week">week</option>
                                <option value="month">Month</option>
                            </select>
                            
                            <select class="form-control col-md-5 float-right project mr-2" id="project" >
                            <?php foreach($projects as $k=>$v){ ?>
                                    <option @if(auth()->user()->default_project==$k) selected @endif value="<?php echo $k; ?>"><?php echo $v; ?></option>
                            <?php } ?>
                                
                              
                            </select>
                        </div>
                        <!--<div class="card-tools">
                            @if (auth()->user()->can('access', 'admins add'))
                                <a class="btn btn-success btn-sm" href="{{ route('companies.create') }}">Create Company</a>
                            @endif
                        </div>-->
                    </div>
                     
                    <div class="card-body-1" id="test">
                        
                        <div style="max-width:1000px" class="card-body ct-chart" id="gantt_here" ></div>
                    </div>
                </div>
           
            </div>
        </div>
    </section>
    <style>
  .gantt_task_scale{
    /*//background-color:#ffeb8a!important;*/
    text-align: center;      
  }
    /*//add only one level of child*/
    .nested_task .gantt_add{
        display: none !important;
    }
</style>

<script src="plugins/jquery/jquery.min.js"></script>
<script type="text/javascript">
	gantt.config.order_branch = true;
gantt.config.order_branch_free = true;
gantt.config.date_format = "%Y-%m-%d %H:%i:%s";

gantt.init("gantt_here");
gantt.config.open_tree_initially = true;
/*gantt.load("gantt/api/data");*/

//add only one level of child
gantt.templates.grid_row_class = function( start, end, task ){
    if ( task.$level > 0 ){
        return "nested_task"
    }
    return "";
};
var dp = new gantt.dataProcessor("/");
dp.init(gantt);
dp.setTransactionMode("REST");

$(document).ready(function(){
    
    var selItem = sessionStorage.getItem("project"); 
	var prev_tasks = sessionStorage.getItem("opened_task"); 
    if (prev_tasks !== null) {
        var String = prev_tasks.split(",");
        
        $.each(String, function( index, value ) {
            var par = $("div").find("[data-task-id='" + value + "']");
            console.log(value)
        });
    }
    
    if (selItem !== null) {
        $('#project').val(selItem);
    }
    
    var selItem_datecal = sessionStorage.getItem("date_cal"); 
    
    if (selItem_datecal !== null) {
        $('#select_cal').val(selItem_datecal);
    }




    
    $(function () {
        yourFunction(); //this calls it on load
        scale_get(); 
        gantt.refreshData();
        gantt.clearAll();
    
        //document.getElementById(gantt_here).innerHTML = "";
        $(".project").change(yourFunction);
        
    });
    
    function yourFunction() {
        var aa = $( ".project" ).val();
        
        sessionStorage.setItem("project", aa);
        gantt.refreshData();
        gantt.clearAll();
        gantt.load("gantt/api/data/"+aa);  
        $(".calen").change(scale_get);  
    }
    
    
    
    function scale_get() {
       
        var calend = $( ".calen" ).val();
        
        sessionStorage.setItem("date_cal", calend);
        
        if(calend == "day"){
            gantt.config.scales = [
                {unit: "week", step: 1, format: "Week # %W"},
                { unit: "day", step: 1, date: "%d %M" }
            ];
            gantt.render();
    
        }
        if(calend == "month"){
            gantt.config.scales = [                         
                 {unit: "year", step: 1, format: " "},
                        {unit: "month", step: 1, format: " %M"}
            ];                                              
            
            gantt.render();
            /*gantt.config.scales = [
                {unit: "year", step: 1, format: " "},
                {unit: "month", step: 1, format: " %M"}
            ];*/
        }
        if(calend == "week"){
            gantt.config.scales = [
                {unit: "year", step: 1, format: "%F"},
                {unit: "week", step: 1, format: "W %W"}
            ];
           gantt.render();
    
        }
    }


});
window.onbeforeunload = function(event)
    {
        var objects = $(".gantt_folder_open");
        for (var obj of objects) {
        //console.log(obj);
        


        var img = $(".gantt_cell").find(".gantt_folder_open");
        //console.log(img.length)
        len = img.length;
        var arr = [];
        if( len > 0 ){
        
        $.each(img, function( index, value ) {
        var par2 = $(this).parent('div')
        var open_id = par2.parent('div').attr('data-task-id')
        
        arr.push(open_id);
        });
        
        //var dat = arr.join('');
        }
           //console.log(dat) 
           //console.log(arr) 
           console.log(sessionStorage.setItem("opened_task", arr)); 


        }
       
    };
</script>
</body>
@stop
