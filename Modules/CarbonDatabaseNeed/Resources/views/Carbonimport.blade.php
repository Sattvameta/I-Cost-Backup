@extends('user::layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
    
      <div class="col-sm-12">
        
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
  <!-- Default box -->
  <div class="card">
    <div class="card-header">
        <h2 >Carbon Database</h2>
        
     
        
    </div>
      
         <div class="card-body">

                               

            <button onclick="window.location.href='{{ asset('uploads/samples/carbon_import.xls') }}'"  type="button" class="mb-2 mr-2 btn-icon-vertical btn btn-info">
            <i class="pe-7s-cloud-download btn-icon-wrapper"></i>Download Excel Sample
            </button>
			 
		                             
		
      </div>
    <div class="card-body">
        @include('layouts.flash.alert')
        <form action="{{ route('carbonprojupload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                        {{ Form::label('file', 'File') }}<span class="asterisk">*</span>
                        {{ Form::file('file', [
                            'class' => "form-control",
                            'id' => "file",
                        ]) }}
                       
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {{Form::submit('Submit', [ 'class' => "btn btn-primary btn-flat" ])}}
						  <a class="btn btn-primary btn-flat" href="{{ route('carbondatabase') }}">Back</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
  </div>
</section>

@stop