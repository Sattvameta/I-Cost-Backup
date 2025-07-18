@extends('user::layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
          
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('documentmanager') }}"><i class="fa fa-dashboard"></i>Central Doc Manager</a></li>
                    <li class="breadcrumb-item active">Central Doc Manager</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    
      <div class="card-body">

<button onclick="window.location.href='{{ route('documentmanager') }}'"  type="button" class="mb-2 mr-2 btn-icon-vertical btn btn-info">
<i class="pe-7s-back btn-icon-wrapper"></i>Back
</button>

     </div>
    <!-- Default box -->
    <div class="card">
	
        <div class="card-header">
            <h3 class="card-title">Central Doc Manager</h3>
            
        </div>
        <div class="card-body">
        <table class="table table-bordered">
       <form action="{{ route('documentmanager.fileUpload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
			
                <div class="col-md-6">
				@foreach($doc as $docs)
                    <input type="text" name="img_name" id="img_name" class="form-control" value="{{$docs->cer_or_delnote}}" readonly>
					 <input type="hidden" name="img_url" id="img_url" class="form-control" value="https://i-cost.co.uk/uat/{{$docs->storage}}/{{$docs->cer_or_delnote}}">
               @endforeach
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-success">Blockchain</button>
                </div>
   
            </div>
        </form> 
        </table>
            
        </div>
    </div>
    <!-- /.card -->

</section>

@stop
