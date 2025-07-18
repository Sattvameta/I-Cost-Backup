@extends('layouts.admin.master')

@section('title') Settings @stop

@section('per_page_style')

@stop
@section('per_page_script')

@stop
@section('content')
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Manage Setting
                <small>Add New setting</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{url('dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="javascript:void(0)" class="active">Settings</a></li>
            </ol>
        </section>
        <section class="content" data-table="settings">
            <div class="row">
                <div class="col-md-8">
                    <div class="box box-info settings">

                        <div class="box-header with-border">
                            <h3 class="box-title">Edit Setting</h3>
                            <a href="{{route('setting.general')}}" class="btn btn-default pull-right" title="Back"><i
                                        class="fa fa-fw fa-chevron-circle-left"></i> Back</a></div>
                        <!-- /.box-header -->

                        <form method="post" enctype="multipart/form-data" accept-charset="utf-8" role="form"
                              action="{{route('setting.general.update',['id' => $settings->id])}}">
                            @csrf
                            @method('PUT')
                            <div style="display:none;">
                            </div>
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="input text required"><label for="title">Title</label>
                                                <input type="text" name="title" class="form-control" placeholder="Title" value="@if(isset($settings->title)){{$settings->title}}@endif" required="required" maxlength="150" id="title">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input text required">
                                                <label for="slug">Constant/Slug</label>
                                                <input type="text" name="slug" class="form-control" value="@if(isset($settings->slug)){{$settings->slug}}@endif" placeholder="Constant/Slug" maxlength="255" id="slug">
                                            </div>
                                            <p class="help-block">No space, separate each word with underscore. (if you
                                                want auto generated then please leave blank)</p>
                                        </div>
                                        <div class="form-group">
                                            <div class="input select required">
                                                <label for="field-type">Field Type</label>
                                                <select name="field_type" class="form-control" placeholder="Field Type" required="required" id="field-type">
                                                    <option value="text" @if(isset($settings->field_type) && $settings->field_type == 'text')selected @endif>Text</option>
                                                    <option value="checkbox" @if(isset($settings->field_type) && $settings->field_type == 'checkbox')selected @endif>Yes/No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group field-textarea-type" style="">
                                            <div class="input textarea">
                                                <label for="setting_textarea">Config Value</label>
                                                <textarea name="config_value" class="form-control" id="setting_textarea" placeholder="Config Value" rows="5">@if(isset($settings->config_value)){{$settings->config_value}}@endif</textarea>
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
                        </form>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="box box-warning">
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
        </section>
@stop