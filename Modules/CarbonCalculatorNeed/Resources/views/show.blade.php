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
						 <table class="table table-bordered table-striped" id="user_table">
					   <thead>
					  <tr>
						<th width="30%" style="background: #ecaab8;">Quantity </th><td width="30%" style="background: #dfdada;"><b>{{$carbon->quantity}}</b></td><td width="30%" style="background: #dfdada;"><b>Mass : </b>{{$carbon->mass}}X{{$carbon->quantity}}kg/m<sup>3</sup></td>
						</tr>
						<tr>
							<th width="30%" style="background: #ecaab8;">Materials/Type</th><td width="30%" style="background: #dfdada;">{{$carbon->materials}}</td>
							<td style="background: #dfdada;"><b>Factor (A1 to A3):</b> {{$carbon->factors}}</td>
						</tr>
						<tr>
							<th width="30%" style="background: #ecaab8;">Transport</th><td width="30%" style="background: #dfdada;">{{$carbon->transport}}</td>
							<td style="background: #dfdada;"><b>Factor (A4):</b> {{$carbon->transport_factor}}</td>
						</tr>
						<tr>
							<th width="30%" style="background: #ecaab8;" style="background: #dfdada;">Wastage</th><td width="30%" style="background: #dfdada;">{{$carbon->wastage}}</td>
							<td style="background: #dfdada;"><b>Factor (A5):</b> <br>WF Factor ={{$carbon->wastage_factor}}<br>WF Factor X (A1-A3+A4+0.005+0.013) = {{$carbon->a_five}}</td>
						</tr>
						
						<th style="background: #ecaab8;">Total Co<sub>2</sub> (t co<sub>2</sub>e)</th><td style="background: #dfdada;"><b>Value : {{$carbon->Total}} </b> </td><td style="background: #dfdada;"><b></b> Mass X Factor (A1 to A3)+Mass X Factor (A4)+ Mass X Factor (A5)</td>
					   </thead>
					   @endforeach
					   <tbody>
                       
					   </tbody>
					   
					</table>
                    </div>
                </div>
            </div>
        </div>
    </section>
	
@stop
@push('scripts')

 
 
@endpush