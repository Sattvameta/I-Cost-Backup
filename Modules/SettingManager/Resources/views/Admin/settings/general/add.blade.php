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

                   <div class="card-body box box-info settings">

                        <div class="box-header with-border">
                            <h3 class="box-title">{{ !empty($settings) ? 'Edit General Setting' : 'Add General Setting' }}</h3><hr>
                            <a href="{{route('setting.general')}}" class="btn btn-default float-sm-right" title="Back"><i
                                        class="fa fa-fw fa-chevron-circle-left"></i> Back</a></div>
                        <!-- /.box-header -->
                        @if(isset($settings))
                            {{ Form::model($settings, ['route' => ['setting.general.update', $settings->id], 'method' => 'patch']) }}
                        @else
                         {{ Form::open(['route' => 'setting.general.store']) }}
                        @endif
                            <div style="display:none;">
                            </div>
                            <div class="box-body">
                                <div class="row">
                                        {{ Form::hidden('manager', 'general') }}
                                    <div class="col-md-12">
                                        <div class="form-group required {{ $errors->has('title') ? 'has-error' : '' }}">
                                            <label for="title">Title</label>
                                            {{ Form::text('title', old('title'), ['class' => 'form-control','placeholder' => 'Title']) }}
                                            @if($errors->has('title'))
                                            <span class="help-block">{{ $errors->first('title') }}</span>
                                            @endif
                                        </div>
                                        <div class="form-group {{ $errors->has('slug') ? 'has-error' : '' }}">
                                                <label for="slug">Constant/Slug</label>
                                                {{ Form::text('slug', old('slug'), ['class' => 'form-control','placeholder' => 'Constant/Slug' ,'readonly' => isset($settings) ? true : false]) }}
                                                <p class="help-block">No space, separate each word with underscore. (if you want auto generated then please leave blank)</p>
                                            </div>
                                        <div class="form-group hide">
                                            <div class="input select required">
                                                <label for="field-type">Field Type</label>
                                                <select name="field_type" class="form-control" placeholder="Field Type" required="required" id="field-type">
                                                    <option value="text">Text</option>
                                                    <option value="checkbox">Yes/No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group required {{ $errors->has('config_value') ? 'has-error' : '' }}" style="">
                                            <div class="input">
                                                <label for="setting_textarea">Config Value</label>
                                                {{ Form::textarea('config_value', old('config_value'), ['class' => 'form-control','placeholder' => 'Config Value', 'rows' => 5]) }}
                                                @if($errors->has('config_value'))
                                                <span class="help-block">{{ $errors->first('config_value') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.row -->
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button class="btn btn-primary btn-flat" title="Submit" type="submit"><i
                                            class="fa fa-fw fa-save"></i> Submit
                                </button>
                                <a href="{{route('setting.general')}}" class="btn btn-warning btn-flat" title="Back"><i class="fa fa-fw fa-chevron-circle-left"></i> Back</a>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
  </div>
                 
                <div class="col-md-4">
                     <div class="card card-primary">
                    <div class="card-body box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <i class="fa fa-exclamation"></i> Important Rules
                            </h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <p>
                                For each config settings that would be added to the system, make sure it has these
                                constant/slug:
                            </p>
                            <ul>
                                <li>
                                    <small class="label bg-yellow">
                                        SITE_TITLE
                                    </small>
                                    - Will be replaced by website title from the admin settings.
                                </li>
                                <li>
                                    <small class="label bg-yellow">
                                        ADMIN_EMAIL
                                    </small>
                                    - Will be replaced by admin email from the admin settings.
                                </li>
                                <li>
                                    <small class="label bg-yellow">
                                        FROM_EMAIL
                                    </small>
                                    - Will be replaced by email from the admin settings.
                                </li>
                                <li>
                                    <small class="label bg-yellow">
                                        WEBSITE_OWNER
                                    </small>
                                    - Will be replaced by Owner name from admin settings.
                                </li>
                                <li>
                                    <small class="label bg-yellow">
                                        TELEPHONE
                                    </small>
                                    - Will be replaced by phone number from admin settings.
                                </li>
                                <li>
                                    <small class="label bg-yellow">
                                        ADMIN_PAGE_LIMIT
                                    </small>
                                    - Will be replaced by admin page limit from admin settings.
                                </li>
                                <li>
                                    <small class="label bg-yellow">
                                        FRONT_PAGE_LIMIT
                                    </small>
                                    - Will be replaced by front page limit from admin settings.
                                </li>
                                <li>
                                    <small class="label bg-yellow">
                                        ADMIN_DATE_FORMAT
                                    </small>
                                    - Will be replaced by admin date format from admin settings.
                                </li>
                                <li>
                                    <small class="label bg-yellow">
                                        ADMIN_DATE_TIME_FORMAT
                                    </small>
                                    - Will be replaced by admin date time format from admin settings.
                                </li>
                                <li>
                                    <small class="label bg-yellow">
                                        FRONT_DATE_FORMAT
                                    </small>
                                    - Will be replaced by front date format from admin settings.
                                </li>
                                <li>
                                    <small class="label bg-yellow">
                                        FRONT_DATE_TIME_FORMAT
                                    </small>
                                    - Will be replaced by front date time format from admin settings.
                                </li>
                                <li>
                                    <small class="label bg-yellow">
                                        CONTACT_US_TEXT
                                    </small>
                                    - Will be replaced by front date time format from admin settings.
                                </li>
                                <li>
                                    <small class="label bg-yellow">
                                        GOOGLE_MAP_KEY
                                    </small>
                                    - Will be replaced by front date time format from admin settings.
                                </li>
                                <li>
                                    <small class="label bg-yellow">
                                        OFFICE_ADDRESS
                                    </small>
                                    - Will be replaced by front date time format from admin settings.
                                </li>

                                <li>
                                    <small class="label bg-yellow">
                                        DEVELOPMENT_MODE
                                    </small>
                                    - Will be replaced by debug mode from admin settings.
                                </li>

                            </ul>
                        </div><!-- ./box-body -->
                    </div>
                </div>
 </div>
            </div>
        </section>
    </div></div>
</section>
@stop
