@extends('user::layouts.master')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{route('setting.general')}}"><i class="nav-icon fas fa-cogs"></i> Settings</a></li>                    
                    <li class="breadcrumb-item active">{{ !empty($settings) ? 'Edit General Setting' : 'Add General Setting' }}</li>
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
                <div class="col-md-8">
                    <div class="card card-primary">

                        <div class="card-body box box-info">
                            <div class="box-header">
                                <h3 class="box-title"><span class="caption-subject font-green bold uppercase">List Settings</span></h3><hr>
                                <div class="box-tools" >
                                    <button onclick="window.location.href='{{route('setting.general.add')}}'"  type="button" class="float-sm-right mb-2 mr-2 btn-icon-vertical btn btn-success">
                                <i class="pe-7s-plus btn-icon-wrapper"></i>New Setting
                                </button> 
                                    
                                    <button onclick="window.location.href='{{route('dashboard')}}'"  type="button" class="mb-2 mr-2 btn-icon-vertical btn btn-info">
                                <i class="pe-7s-back btn-icon-wrapper"></i>Back
                                </button> 
                                   
                                </div>
                            </div><!-- /.box-header -->
                      
                                                <div class="box-body table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>

                                            <th scope="col"><a href="{{ URL::route('setting.general',['sort' => 'title','direction'=> request()->direction == 'asc' ? 'desc' : 'asc']) }}">Title</a></th>
                                            <th scope="col"><a href="{{ URL::route('setting.general',['sort' => 'slug','direction'=> request()->direction == 'asc' ? 'desc' : 'asc']) }}">Constant</a></th>
                                            <th scope="col">Value</th>
                                            <th scope="col" class="actions" style="width: 20%;">Actions</th>
                                        </tr>
                                    </thead>
                                    @if($settings->count() > 0)
                                    <tbody>
                                        @php
                                        $i = 1;
                                        @endphp
                                        @foreach($settings as $setting)
                                        <tr>

                                            <td>{{$setting->title}}</td>
                                            <td>{{$setting->slug}}</td>
                                            <td>{{$setting->config_value}}</td>
                                            <td class="actions">
                                                <div class="btn-group">
                                                    <a href="{{url('settings/general/view/'.$setting->id)}}" class="btn btn-warning btn-sm" data-toggle="tooltip" alt="View setting" title="" data-original-title="View"><i class="fa fa-fw fa-eye"></i></a>
                                                    <a href="{{url('settings/general/edit/'.$setting->id)}}" class="btn btn-primary btn-sm" data-toggle="tooltip" alt="Edit" title="" data-original-title="Edit"><i class="fa fa-edit"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        @php
                                        $i++;
                                        @endphp
                                        @endforeach
                                    </tbody>
                                    @else
                                    <tfoot>
                                        <tr>
                                            <td colspan='7' align='center'> <strong>Record Not Available</strong> </td>
                                        </tr>
                                    </tfoot>
                                    @endif
                                </table>
                            </div>

                        </div>
                    </div>
                </div> <div class="col-md-4">
                    <div class="card card-primary">

                        <div class="card-body box box-warning">
                            <div class="box-header with-border">
                                <h3 class="box-title">
                                    <i class="fa fa-exclamation"></i> Important Rules
                                </h3>
                            </div><!-- /.box-header -->
                            <div class="box-body">
                                <p>
                                    For each config settings that would be added to the system, make sure it has these constant/slug:
                                </p>
                                <ul>
                                    <li>
                                        <small class="label bg-yellow">
                                            SYSTEM_APPLICATION_NAME
                                        </small> - Will be replaced by website title from the admin settings.
                                    </li>
                                    <li>
                                        <small class="label bg-yellow">
                                            ADMIN_EMAIL
                                        </small> - Will be replaced by admin email from the admin settings.
                                    </li>
                                    <li>
                                        <small class="label bg-yellow">
                                            FROM_EMAIL
                                        </small> - Will be replaced by email from the admin settings.
                                    </li>
                                    <li>
                                        <small class="label bg-yellow">
                                            WEBSITE_OWNER
                                        </small> - Will be replaced by Owner name from admin settings.
                                    </li>


                                    <li>
                                        <small class="label bg-yellow">
                                            CONTACT_ADDRESS
                                        </small> - Will be replaced by front date time format from admin settings.
                                    </li>

                                    <li>
                                        <small class="label bg-yellow">
                                            DEVELOPMENT_MODE
                                        </small> - Will be replaced by debug mode from admin settings.
                                    </li>


                                </ul>
                            </div><!-- ./box-body -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</section>
@stop
