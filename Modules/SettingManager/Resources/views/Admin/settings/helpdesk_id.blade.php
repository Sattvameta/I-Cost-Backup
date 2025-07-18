@extends('user::layouts.master')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Query Details</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{route('helpdesk')}}"><i class="fas fa-lock nav-icon"></i> Help Desk Query</a></li>  
                    <li class="breadcrumb-item"><a href=""><i class="fas fa-lock nav-icon"></i> Query Details </a></li>
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





                        <div class="col-md-7" >

                        <div class="row">   
                        <label class="col-md-3" for="exampleInputEmail1"><b>Query Id</b></label>
                        <div class="col-md-4 form-group">
                        <input type="text"  name="query_type" value="ICQ<?php print_r(sprintf("%04d", $query_data->id));?>" disabled="disabled" class="form-control" style="color:#000000;">
                        </div>
                        </div>
                        <div class="row">   
                        <label class="col-md-3" for="exampleInputEmail1"><b>Query Type</b></label>
                        <div class="col-md-4 form-group">
                        <input type="text"  name="query_type" value="{{$query_data->query_type}}" disabled="disabled" class="form-control" style="color:#000000;">
                        </div>
                        </div>
                        <div class="row">   
                        <label class="col-md-3" for="exampleInputEmail1"><b>Query Date</b></label>
                        <div class="col-md-4 form-group">
                        <input type="text"  name="query_type" value="{{$query_data->query_date}}" disabled="disabled" class="form-control" style="color:#000000;">
                        </div>
                        </div>
                        <div class="row">   
                        <label class="col-md-3" for="exampleInputEmail1"><b>Company Name</b></label>
                        <div class="col-md-4 form-group">
                        <input type="text"  name="query_type" value="{{$query_data->company_name}}" disabled="disabled" class="form-control" style="color:#000000;">
                        </div>
                        </div>
                        <div class="row">   
                        <label class="col-md-3" for="exampleInputEmail1"><b>Final Status</b></label>
                        <div class="col-md-4 form-group">
                        <input type="text"  name="query_type" value="<?php if($query_data->status == '' || $query_data->status== null){ echo "Not Processed Yet"; }else{ echo ucfirst($query_data->status);  } ?>" disabled="disabled" class="form-control" style="color:#000000;">
                        </div>
                        </div>
                        <div class="row">   
                        <label class="col-md-3" for="exampleInputEmail1"><b>Query Details</b></label>
                        <div class="col-md-4 form-group">
                        <textarea disabled="disabled" cols="54" class="form-group">{{$query_data->query_details}}</textarea>
                        </div>
                        </div>
                        <div class="row">
                        <label class="col-md-3" for="exampleInputEmail1" style="color:#000000;"><b>Query Related Doc</b></label>
                        <div class="col-md-4">
                        <?php $ext=strrchr($query_data->query_file,'.');
                        if($ext != ''){ 
                        if($ext==".jpg" || $ext==".jpeg" || $ext==".JPG" || $ext==".JPEG" || $ext==".png" || $ext==".PNG")
                        {
                
                        $image='/uploads/helpdesk_files/'.$query_data->query_file;
                        }
                        else
                        {
                        $image='/uploads/helpdesk_files/'.$query_data->query_file;
                        }
                        
                        ?>  <a href="{{URL::asset($image)}}" target="_blank" download><img class="preview img-thumbnail" style="width: 150px;" src="{{URL::asset($image)}}"></a>
                        <?php }else{
                        echo htmlentities("File NA");
                        }
                        ?>
                        </div>
                        </div>
                        <br>
                        <?php if (auth()->user()->isRole('Super Admin')) { ?>
                        <div class="row">  
                        <div class="col-md-12">
                        <div class="form-group" align="center">
                        <!--<button name="submit" type="submit" class="btn btn-primary" style="border: none;"><b>Manage</b></button>-->
                        <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Manage</button>
                        <div class="clearfix"></div>
                        </div>
                        </div>
                        </div>
                        <?php } ?>
                    
                        </div>  
                        <div class="col-md-5" >
                            <a data-toggle="collapse" href="#collapse1">Previous Status</a>
                            <div id="collapse1" class="panel-collapse collapse">
                           
                           
                            @foreach($ret as $data)
                              
                               
                                <div class="row">   
                                <label class="col-md-4" for="exampleInputEmail1" style="color:#000000;"><b>Remark</b></label>
                                <div class="col-md-6 form-group">
                                <input type="text"  name="query_type" value="<?php echo  htmlentities($data->remark); ?>" disabled="disabled" class="form-control" style="color:#000000;">
                                </div>
                                </div>
                                <div class="row">   
                                <label class="col-md-4" for="exampleInputEmail1" style="color:#000000;"><b>Status</b></label>
                                <div class="col-md-6 form-group">
                                <input type="text"  name="query_type" value="<?php echo  htmlentities($data->status); ?>" disabled="disabled" class="form-control" style="color:#000000;">
                                </div>
                                </div>
                            @endforeach
                            </div>
                        </div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title">Manage Query</h4>
        <button type="button" class="close pull-right" data-dismiss="modal">&times;</button>
      </div>
<form action="{{ route('queryreview') }}" name="ffk3w" method="post" enctype="multipart/form-data">  
{{ csrf_field() }}
<div class="modal-body">
<div class="form-group">
    <input type="hidden" name="query_id" value="<?php echo $query_data->id; ?>">
    <label for="recipient-name" class="col-form-label">Status:</label>
    <select required aria-required="true" name="status" id="status" class="form-control" >
        <option value="">Select</option> 
        <option value="In Progress">In Process</option>
        <option value="Closed">Closed</option>
    </select>
</div>
<div class="form-group">
<label for="message-text" class="col-form-label">Remark:</label>
<textarea class="form-control" id="remark" name="remark"></textarea>
</div>



</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
<button type="submit" name="update" class="btn btn-primary">Save</button>
</div>

</form>
</div>

</div>
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

