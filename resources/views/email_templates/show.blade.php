@extends('user::layouts.master')
@section('content')
<style>
    .box{border-top: 0px !important;}
</style>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{route('emailtemplates')}}"> Email Templates</a></li>                    
                    <li class="breadcrumb-item active">View</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
     <div class="card-body">

            <button onclick="window.location.href='{{route('emailtemplates')}}'"  type="button" class="mb-2 mr-2 btn-icon-vertical btn btn-info">
            <i class="pe-7s-back btn-icon-wrapper"></i>Back
            </button>                       

      </div>

    <!-- Default box -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">View Email Templates</h3><hr>

         
        </div>
        <div class="card-body">
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
               <ul class="list-group list-group-unbordered mb-3">
                                                        <li class="list-group-item">
                                                            <b>Type</b> <a class="float-right">{{$emailtemplate->type}}</a>
                                                        </li>

                                                        </li>

                                                        <li class="list-group-item">
                                                            <b>Status</b> <a class="float-right">{{($emailtemplate->status==1)?'Active':'Inactive'}}</a>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <b>Created At</b> <a class="float-right">{{$emailtemplate->created_at}}</a>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <b>Updated At</b> <a class="float-right">{{$emailtemplate->updated_at}}</a>
                                                        </li>
                                                    </ul>
               
              </div>
                </div>
              <!-- /.card-body -->
            </div>
            
                        <div class="col-md-9">

                            <div class="card card-primary card-outline">

                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane" id="info">
 
                                                    <ul class="list-group list-group-unbordered mb-3">
                                                        <li class="list-group-item">
                                                            <b>Subject</b> : {{$emailtemplate->subject}}
                                                        </li>
                                                        <li class="list-group-item">
                                                            <b>Template</b> :<br>
                                                            {!! $emailtemplate->template !!}
                                                        </li>
                                                        </li>
                                                    </ul>

                                               
                                        </div>
                                        <!-- /.tab-pane -->
                                    </div>
                                    <!-- /.tab-content -->
                                </div><!-- /.card-body -->
                            </div>
                            <!-- /.nav-tabs-custom -->
                        </div>

                        <!-- /.card -->

                    </div>

                </div>
            </section>
            <!-- /.card-body -->
        </div>


</section>

@stop