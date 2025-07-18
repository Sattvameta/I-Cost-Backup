@extends('user::layouts.master')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Query</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{route('helpdesk')}}"><i class="fas fa-lock nav-icon"></i> Help Desk Query</a></li>  
                    <li class="breadcrumb-item"><a href=""><i class="fas fa-lock nav-icon"></i> Create Query </a></li>
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
        <section class="card card-body box box-default settings" data-table="settings">
            <div class="row">

                        <form action="{{ route('savequery') }}" name="ffk3w" method="post" enctype="multipart/form-data">  
                        @csrf



                        <div class="col-md-12" >

                       
                        <div class="row">   
                        <label class="col-md-5" for="exampleInputEmail1"><b>Query Type</b></label>
                        <div class="col-md-7 form-group">
                        <select required aria-required="true" name="query_type" id="query_type" class="form-control" >
                        <option value="">Select</option>
                        <option value="Features Not Working"> Features Not Working </option>
                        <option value="Query With Operating System"> Query With Operating System </option>
                        <option value="Help"> Help </option>
                        </select>
                        </div>
                        </div>
                        <div class="row">   
                        <label class="col-md-5" for="exampleInputEmail1"><b>Query Details (max 2000 words)</b></label>
                        <div class="col-md-7 form-group">
                            <textarea  class="form-control" name="querydetails" required="required" cols="10" rows="5"  maxlength="2000"></textarea>
                        </div>
                        </div>
                        
                        
                       
                        <div class="row">
                        <label class="col-md-5" for="exampleInputEmail1" style="color:#000000;"><b>Query Related Doc(if any)</b></label>
                        <div class="col-md-7">
                        <input type="file" name="queryfile" class="form-control">
                        </div>
                        </div>
                        <br>
                        <div class="row" >  
                            <button name="submit" type="submit" class="btn btn-primary pull-right" style="border: none;"><b>Submit</b></button>
                      
                        </div>
                    
                        </div>  
                        </form>





            </div>
             
           
        </section>
    </div>
</section>
@stop


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

