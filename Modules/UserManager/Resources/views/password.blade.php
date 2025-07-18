@extends('user::layouts.master')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      
      <div class="col-sm-12">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
          <li class="breadcrumb-item active">Password</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    @include('layouts.flash.alert')
    
     <div class="card-body">

            <button onclick="window.location.href='{{ route('dashboard') }}'"  type="button" class="mb-2 mr-2 btn-icon-vertical btn btn-info">
            <i class="pe-7s-back btn-icon-wrapper"></i>Back
            </button>                       

      </div>
    <div class="card">
        {{ Form::open(['route' => ['users.change.password'], 'method' => 'post', 'enctype'=> 'multipart/form-data']) }}
        <div class="card-header">
            <h3 class="card-title">Change Password</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('old_password')) has-error @endif">
                        {{ Form::label('old_password', 'Old password') }}<span class="asterisk">*</span>
                        {{ Form::text('old_password', '', [
                            'class' => "form-control old_password",
                            'id' => "old_password",
                        ]) }}
                        @if($errors->has('old_password'))
                        <span style="color:red" class="error">{{ $errors->first('old_password') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('new_password')) has-error @endif">
                        {{ Form::label('new_password', 'New password') }}<span class="asterisk">*</span>
                        {{ Form::text('new_password', '', [
                            'class' => "form-control new_password",
                            'id' => "new_password",
                        ]) }}
                        @if($errors->has('new_password'))
                            <span style="color:red" class="error">{{ $errors->first('new_password') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('confirm_password')) has-error @endif">
                        {{ Form::label('confirm_password', 'Confirm password') }}<span class="asterisk">*</span>
                        {{ Form::text('confirm_password', '', [
                            'class' => "form-control confirm_password",
                            'id' => "confirm_password"
                        ]) }}
                        @if($errors->has('confirm_password'))
                            <span style="color:red" class="error">{{ $errors->first('confirm_password') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            {{ Form::submit('Change password', [ 'class' => "btn btn-primary btn-flat" ]) }}
        </div>
        {{ Form::close() }}
    </div>
</section>
@stop