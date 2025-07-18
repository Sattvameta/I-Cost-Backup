@extends('user::layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Slider Detail</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">

                    <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{route('sliders')}}">Sliders</a></li>
                    <li class="breadcrumb-item active">View Slider</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">View Slider</h3>

            <div class="card-tools">
                <div class="box-tools pull-right">
                    <a href="{{route('sliders')}}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="card card-primary card-outline">
                                <div class="card-header p-2">
                                    <ul class="nav nav-pills">
                                        <li class="nav-item"><a class="nav-link active" href="#info" data-toggle="tab">Basic Info</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#description" data-toggle="tab">Description</a></li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane" id="info">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <?php
                                                    if (isset($slider->image) && file_exists(public_path() . '/uploads/sliders/' . $slider->image) && !empty($slider->image)) {
                                                        $image_url = '/uploads/sliders/' . $slider->image;
                                                    } else {
                                                        $image_url = '/images/placeholder.png';
                                                    }
                                                    ?>
                                                    <img class="preview img-thumbnail" style="width: 100%;" src="{{URL::asset($image_url)}}">
                                                </div>
                                                <div class="col-md-9">
                                                    <ul class="list-group list-group-unbordered mb-3">
                                                        <li class="list-group-item">
                                                            <b>Title</b> <a class="float-right">{{Arr::get($slider, 'title', '')}}</a>
                                                        </li>

                                                        </li>
                                                        
                                                        <li class="list-group-item">
                                                            <b>Status</b> <a class="float-right">{{($slider->status==1)?'Active':'Inactive'}}</a>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <b>Created At</b> <a class="float-right">{{$slider->created_at->format(config('get.ADMIN_DATE_TIME_FORMAT')) }}</a>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <b>Updated At</b> <a class="float-right">{{$slider->updated_at->format(config('get.ADMIN_DATE_TIME_FORMAT'))}}</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="description">
                                            {!!$slider->description!!}
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
        <!-- /.card -->

</section>

@stop