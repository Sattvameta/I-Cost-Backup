@extends('user::layouts.master')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Manage Companies</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">Companies</a></li>
          <li class="breadcrumb-item active">Edit Company</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <div class="card">
        {{ Form::open(['route' => ['companies.update', $company->id], 'method' => 'patch', 'enctype'=> 'multipart/form-data']) }}
        <input type="hidden" name="id" value="{{ $company->id }}">
        <div class="card-header">
            <h3 class="card-title">Edit Company</h3>
            <div class="card-tools">
                <a class="btn btn-primary btn-sm" href="{{ route('companies.index') }}" title="Back">Back</a>
            </div>
        </div>
        <div class="card-body">
        <div class="row">
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('company_name')) has-error @endif">
                        {{ Form::label('company_name', 'Company name') }}<span class="asterisk">*</span>
                        {{ Form::text('company_name', old('company_name') ? old('company_name') : $company->company_name, [
                            'class' => "form-control company_name",
                            'id' => "company_name",
                        ]) }}
                        @if($errors->has('company_name'))
                            <span class="invalid-feedback">{{ $errors->first('company_name') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('company_contact')) has-error @endif">
                        {{ Form::label('company_contact', 'Company contact') }}<span class="asterisk">*</span>
                        {{ Form::text('company_contact', old('company_contact') ? old('company_contact') : $company->company_contact, [
                            'class' => "form-control company_contact",
                            'id' => "company_contact",
                        ]) }}
                        @if($errors->has('company_contact'))
                            <span class="invalid-feedback">{{ $errors->first('company_contact') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('category_id')) has-error @endif">
                        {{ Form::label('category_id', 'Category') }}<span class="asterisk">*</span>
                        {{ Form::select('category_id', $categories, old('category_id') ? old('category_id') : $company->category_id, [
                            'class' => "form-control select2-input category_id",
                            'id' => "category_id",
                            'data-live-search'=>'true'
                        ]) }}
                        @if($errors->has('category_id'))
                            <span class="invalid-feedback">{{ $errors->first('category_id') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('logo')) has-error @endif">
                        {{ Form::label('logo', 'Company logo') }}
                        {{ Form::file('logo', [
                            'class' => "form-control logo",
                            'id' => "logo",
                        ]) }}
                        @if($errors->has('logo'))
                            <span class="invalid-feedback">{{ $errors->first('logo') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('full_name')) has-error @endif">
                        {{ Form::label('full_name', 'Full name') }}<span class="asterisk">*</span>
                        {{ Form::text('full_name', old('full_name') ? old('full_name') : $company->full_name, [
                            'class' => "form-control full_name",
                            'id' => "full_name",
                        ]) }}
                        @if($errors->has('full_name'))
                            <span class="invalid-feedback">{{ $errors->first('full_name') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('email')) has-error @endif">
                        {{ Form::label('email', 'Email') }}<span class="asterisk">*</span>
                        {{ Form::text('email', old('email') ? old('email') : $company->email, [
                            'class' => "form-control email",
                            'id' => "email",
                        ]) }}
                        @if($errors->has('email'))
                            <span class="invalid-feedback">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('address_line1')) has-error @endif">
                        {{ Form::label('address_line1', 'Address line1') }}
                        {{ Form::text('address_line1', old('address_line1') ? old('address_line1') : $company->address_line1, [
                            'class' => "form-control address_line1",
                            'id' => "address_line1",
                        ]) }}
                        @if($errors->has('address_line1'))
                            <span class="invalid-feedback">{{ $errors->first('address_line1') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('address_line2')) has-error @endif">
                        {{ Form::label('address_line2', 'Address line2') }}
                        {{ Form::text('address_line2', old('address_line2') ? old('address_line2') : $company->address_line2, [
                            'class' => "form-control address_line2",
                            'id' => "address_line2",
                        ]) }}
                        @if($errors->has('address_line2'))
                            <span class="invalid-feedback">{{ $errors->first('address_line2') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('phone')) has-error @endif">
                        {{ Form::label('phone', 'Phone') }}<span class="asterisk">*</span>
                        {{ Form::text('phone', old('phone') ? old('phone') : $company->phone, [
                            'class' => "form-control phone",
                            'id' => "phone",
                        ]) }}
                        @if($errors->has('phone'))
                            <span class="invalid-feedback">{{ $errors->first('phone') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('fax')) has-error @endif">
                        {{ Form::label('fax', 'Fax') }}
                        {{ Form::text('fax', old('fax') ? old('fax') : $company->fax, [
                            'class' => "form-control fax",
                            'id' => "fax",
                        ]) }}
                        @if($errors->has('fax'))
                            <span class="invalid-feedback">{{ $errors->first('fax') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('suburb')) has-error @endif">
                        {{ Form::label('suburb', 'Town') }}
                        {{ Form::text('suburb', old('suburb') ? old('suburb') : $company->suburb, [
                            'class' => "form-control suburb",
                            'id' => "suburb",
                        ]) }}
                        @if($errors->has('suburb'))
                            <span class="invalid-feedback">{{ $errors->first('suburb') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('postcode')) has-error @endif">
                        {{ Form::label('postcode', 'Post code') }}
                        {{ Form::text('postcode', old('postcode') ? old('postcode') : $company->postcode, [
                            'class' => "form-control postcode",
                            'id' => "postcode",
                        ]) }}
                        @if($errors->has('postcode'))
                            <span class="invalid-feedback">{{ $errors->first('postcode') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('status')) has-error @endif">
                        {{ Form::label('status', 'Status') }}
                        {{ Form::select('status', [1=> 'Active', 0=> 'In-active'], old('status') ? old('status') : $company->status, [
                            'class' => "form-control status",
                            'id' => "status",
                        ]) }}
                        @if($errors->has('status'))
                            <span class="invalid-feedback">{{ $errors->first('status') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('file')) has-error @endif">
                        {{ Form::label('file', 'Avatar') }}
                        {{ Form::file('file', [
                            'class' => "form-control file",
                            'id' => "file",
                        ]) }}
                        @if($errors->has('file'))
                            <span class="invalid-feedback">{{ $errors->first('file') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer" style="margin-left: 125px;">
            {{ Form::submit('Update company', [ 'class' => "btn btn-primary btn-flat" ]) }}
        </div>
        {{ Form::close() }}
    </div>
</section>
@stop