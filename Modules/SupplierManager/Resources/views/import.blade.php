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
          <li class="breadcrumb-item active">Import Supplier</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    @include('layouts.flash.alert')
    <div class="card">
        {{ Form::open(['route' => ['suppliers.do.import'], 'method' => 'post', 'enctype'=> 'multipart/form-data']) }}
       
        
                      <div class="card-body">

                        <h5 class="card-title">Import Supplier</h5>
                     

                      
                        <button  onclick="window.open('{{ asset('uploads/samples/supplier_import.xls') }}')" type="button" class="mb-2 mr-2 btn-icon-vertical btn btn-primary">
                        <i class="pe-7s-cloud-download btn-icon-wrapper"></i>Download Excel Sample
                        </button>
                    
                        <button onclick="window.location.href='{{ route('suppliers.index') }}'"  type="button" class="mb-2 mr-2 btn-icon-vertical btn btn-info">
                        <i class="pe-7s-back btn-icon-wrapper"></i>Back
                        </button>

                       

                    </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('category_id')) has-error @endif">
                        {{ Form::label('category_id', 'Category') }}<span class="asterisk">*</span>
                        {{ Form::select('category_id', $categories, old('category_id'), [
                            'class' => "multiselect-dropdown form-control  category_id",
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
                        {{ Form::select('company_id', $companies, old('company_id'), [
                            'class' => "form-control multiselect-dropdown company_id",
                            'id' => "company_id",
                            'data-live-search'=>'true'
                        ]) }}
                        @if($errors->has('company_id'))
                            <span style="color:red" class="">{{ $errors->first('company_id') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('file')) has-error @endif">
                        {{ Form::label('file', 'File') }}
                        {{ Form::file('file', [
                            'class' => "form-control file",
                            'id' => "file",
                            'accept'=>".xlsx, .xls, .csv"
                        ]) }}
                      @if($errors->has('file'))
                        <span style="color:red" class="">{{ $errors->first('file') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            {{ Form::submit('Import', [ 'class' => "btn btn-primary btn-flat" ]) }}
        </div>
        {{ Form::close() }}
    </div>
</section>
@stop