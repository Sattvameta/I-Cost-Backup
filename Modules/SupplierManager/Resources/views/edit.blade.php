@extends('user::layouts.master')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1></h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}">Suppliers</a></li>
          <li class="breadcrumb-item active">Edit Supplier</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    
    <div class="card-body">

<button onclick="window.location.href='{{ route('suppliers.index') }}'"  type="button" class="mb-2 mr-2 btn-icon-vertical btn btn-info">
<i class="pe-7s-back btn-icon-wrapper"></i>Back
</button>

     </div>
    <div class="card">
        {{ Form::open(['route' => ['suppliers.update', $supplier->id], 'method' => 'patch', 'enctype'=> 'multipart/form-data']) }}
        <input type="hidden" name="id" value="{{ $supplier->id }}">
       
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('supplier_name')) has-error @endif">
                        {{ Form::label('supplier_name', 'Supplier name') }}<span class="asterisk">*</span>
                        {{ Form::text('supplier_name', old('supplier_name') ? old('supplier_name') : $supplier->supplier_name, [
                            'class' => "form-control supplier_name",
                            'id' => "supplier_name",
                        ]) }}
                        @if($errors->has('supplier_name'))
                            <span style="color:red" class="error">{{ $errors->first('supplier_name') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('supplier_contact_name')) has-error @endif">
                        {{ Form::label('supplier_contact_name', 'Supplier contact name') }}<span class="asterisk">*</span>
                        {{ Form::text('supplier_contact_name', old('supplier_contact_name') ? old('supplier_contact_name') : $supplier->supplier_contact_name, [
                            'class' => "form-control supplier_contact_name",
                            'id' => "supplier_contact_name",
                        ]) }}
                        @if($errors->has('supplier_contact_name'))
                        <span style="color:red" class="error">{{ $errors->first('supplier_contact_name') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('category_id')) has-error @endif">
                        {{ Form::label('category_id', 'Category') }}<span class="asterisk">*</span>
                        {{ Form::select('category_id', $categories, old('category_id') ? old('category_id') : $supplier->category_id, [
                            'class' => "form-control multiselect-dropdown category_id",
                            'id' => "category_id",
                            'data-live-search'=>'true'
                        ]) }}
                        @if($errors->has('category_id'))
                            <span style="color:red" class="error">{{ $errors->first('category_id') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('company_id')) has-error @endif">
                        {{ Form::label('company_id', 'Company') }}<span class="asterisk">*</span>
                        {{ Form::select('company_id', $companies, old('company_id') ? old('company_id') : $supplier->company_id, [
                            'class' => "form-control multiselect-dropdown company_id",
                            'id' => "company_id",
                            'data-live-search'=>'true'
                        ]) }}
                        @if($errors->has('company_id'))
                            <span style="color:red" class="error">{{ $errors->first('company_id') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('full_name')) has-error @endif">
                        {{ Form::label('full_name', 'Full name') }}<span class="asterisk">*</span>
                        {{ Form::text('full_name', old('full_name') ? old('full_name') : $supplier->full_name, [
                            'class' => "form-control full_name",
                            'id' => "full_name",
                        ]) }}
                        @if($errors->has('full_name'))
                            <span style="color:red" class="error">{{ $errors->first('full_name') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('email')) has-error @endif">
                        {{ Form::label('email', 'Email') }}<span class="asterisk">*</span>
                        {{ Form::text('email', old('email') ? old('email') : $supplier->email, [
                            'class' => "form-control email",
                            'id' => "email",
                        ]) }}
                        @if($errors->has('email'))
                            <span style="color:red" class="error">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('address_line1')) has-error @endif">
                        {{ Form::label('address_line1', 'Address line1') }}
                        {{ Form::text('address_line1', old('address_line1') ? old('address_line1') : $supplier->address_line1, [
                            'class' => "form-control address_line1",
                            'id' => "address_line1",
                        ]) }}
                        @if($errors->has('address_line1'))
                            <span style="color:red" class="error">{{ $errors->first('address_line1') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('address_line2')) has-error @endif">
                        {{ Form::label('address_line2', 'Address line2') }}
                        {{ Form::text('address_line2', old('address_line2') ? old('address_line2') : $supplier->address_line2, [
                            'class' => "form-control address_line2",
                            'id' => "address_line2",
                        ]) }}
                        @if($errors->has('address_line2'))
                            <span style="color:red" class="error">{{ $errors->first('address_line2') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('phone')) has-error @endif">
                        {{ Form::label('phone', 'Phone') }}<span class="asterisk">*</span>
                        {{ Form::text('phone', old('phone') ? old('phone') : $supplier->phone, [
                            'class' => "form-control phone",
                            'id' => "phone",
                        ]) }}
                        @if($errors->has('phone'))
                            <span style="color:red" class="error">{{ $errors->first('phone') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('fax')) has-error @endif">
                        {{ Form::label('fax', 'Fax') }}
                        {{ Form::text('fax', old('fax') ? old('fax') : $supplier->fax, [
                            'class' => "form-control fax",
                            'id' => "fax",
                        ]) }}
                        @if($errors->has('fax'))
                            <span style="color:red" class="error">{{ $errors->first('fax') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('suburb')) has-error @endif">
                        {{ Form::label('suburb', 'Town') }}
                        {{ Form::text('suburb', old('suburb') ? old('suburb') : $supplier->suburb, [
                            'class' => "form-control suburb",
                            'id' => "suburb",
                        ]) }}
                        @if($errors->has('suburb'))
                            <span style="color:red" class="error">{{ $errors->first('suburb') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('postcode')) has-error @endif">
                        {{ Form::label('postcode', 'Post code') }}
                        {{ Form::text('postcode', old('postcode') ? old('postcode') : $supplier->postcode, [
                            'class' => "form-control postcode",
                            'id' => "postcode",
                        ]) }}
                        @if($errors->has('postcode'))
                            <span style="color:red" class="error">{{ $errors->first('postcode') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('status')) has-error @endif">
                        {{ Form::label('status', 'Status') }}
                        {{ Form::select('status', [1=> 'Active', 0=> 'In-active'], old('status') ? old('status') : $supplier->status, [
                            'class' => "form-control status",
                            'id' => "status",
                        ]) }}
                        @if($errors->has('status'))
                            <span style="color:red" class="error">{{ $errors->first('status') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('file')) has-error @endif">
                        {{ Form::label('file', 'Insurance Details') }}
                        {{ Form::file('file', [
                            'class' => "form-control file",
                            'id' => "file",
                        ]) }}
                        @if($errors->has('file'))
                            <span style="color:red" class="error">{{ $errors->first('file') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            {{ Form::submit('Update supplier', [ 'class' => "btn btn-primary btn-flat" ]) }}
        </div>
        {{ Form::close() }}
    </div>
</section>
@stop