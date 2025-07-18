@extends('user::layouts.master')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Manage Setting</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{route('setting.general')}}"><i class="nav-icon fas fa-cogs"></i> Settings</a></li>                    
                    <li class="breadcrumb-item active">Setting Detail</li>
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

                        <div class="card-body box">

                            <div class="box-header"><h3 class="box-title">Office Address</h3>
                                <a href="{{route('setting.general')}}" class="btn btn-default float-sm-right" title="Back"><i
                                        class="fa fa-fw fa-chevron-circle-left"></i> Back</a>
                            </div>

                            <div class="box-body">

                                <table class="table table-hover table-striped">
                                    <tbody><tr>
                                            <th scope="row">Title</th>
                                            <td>{{$settings->title}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Manager</th>
                                            <td>{{$settings->manager}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Constant/Slug</th>
                                            <td>{{$settings->slug}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Config Value</th>
                                            <td>{{$settings->config_value}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Field Type</th>
                                            <td>{{$settings->field_type}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Created</th>
                                            <td>{{$settings->created_at}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Modified</th>
                                            <td>{{$settings->updated_at}}</td>
                                        </tr>
                                    </tbody></table>

                            </div>
                        </div> </div> </div> </div>
        </section>
    </div>
</section>
@stop
