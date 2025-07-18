@extends('user::layouts.masterlist')
@section('content')

<style>
.btn1 {
  background-color: DodgerBlue; /* Blue background */
  border: none; /* Remove borders */
  color: white; /* White text */
  padding: 8px 8px; /* Some padding */
  font-size: 16px; /* Set a font size */
  cursor: pointer; /* Mouse pointer on hover */
}

/* Darker background on mouse-over */
.btn1:hover {
  background-color: RoyalBlue;
}
</style>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>CO<sub>2</sub> Calculator</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a>
                        </li>                 
                        <li class="breadcrumb-item active">CO<sub>2</sub> Calculator</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        @include('layouts.flash.alert')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                               <div class="form-group">
                                    <div class="col-sm-6">
		<a class="mb-2 mr-2 btn-icon-vertical btn btn-success" href="{{ route('carboncalculator') }}">Back</a>
		</div>
                                </div>
                            </div>
                        </div>
						 @foreach($carbon_formula as $carbon)
						 
						   {{ Form::open(['route' => ['update', $carbon->id], 'method' => 'patch', 'enctype'=> 'multipart/form-data']) }}
						 <div class="col-sm-3">
						 <h6><b>Quantity</b></h6>
						 </div>
						   <div class="col-sm-3">
						 <table class="table table-bordered table-striped" id="user_table">
					<input type="text" name="quantity" class="form-control" value="{{$carbon -> quantity}}"/>
				
					   
					</table>
					 <div class="col-sm-6" style="
   margin-left: 125px;">
            {{ Form::submit('Update', [ 'class' => "btn btn-primary btn-flat" ]) }}
        </div>
        {{ Form::close() }}
						 @endforeach
                    </div>
                     </div>
                </div>
            </div>
        </div>
    </section>
	
@stop
