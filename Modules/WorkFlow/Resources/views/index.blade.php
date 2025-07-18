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
                    <h1>Workflow</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a>
                        </li>                 
                        <li class="breadcrumb-item active">Workflow</li>
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
                         <form method="post" id="dynamic_form">
						 <span id="result"></span>
						 <table class="table table-bordered table-striped" id="user_table">
						 <h4>Life cycle</h4>
					   <thead>
						<tr>
							<th >Industry</th>
						   <td>Construction <input type="checkbox" id="myCheck" onclick="myFunction()"></td>
                           <td>Retail<input type="checkbox" id="Check" onclick="Function()"></td>
						   <td>Manufacturing <input type="checkbox" id="Chk" onclick="Functionchk()"></td>
						   <td>Services & Consultancy <input type="checkbox" id="Ck" onclick="Functionck()"></td>
						 </tr>
					   </thead>
					</table>
                 <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Calculation for Carbon Footprint
                                <small> </small>
                            </h4>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <img src="../public/storage/settings/workflowcarbon.PNG" style="width: 600px;">
                        </div>
                    </div>
                </div><br>
				<div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Workflow 	

                                <small> </small>
                            </h4>
							
                        </div>
                        <div class="card-body table-responsive p-0">
                             <p id="text" style="display:none"><img src="../public/storage/settings/lifeflow.jpeg" style="width: 900px;"></p>
                        </div>
                       <div class="card-body table-responsive p-0">
                             <p id="textor" style="display:none"><img src="../public/storage/settings/retail.PNG" style="width: 900px;"></p>
                        </div>
						<div class="card-body table-responsive p-0">
                             <p id="txt" style="display:none"><img src="../public/storage/settings/manufacture.PNG" style="width: 900px;"></p>
                        </div>
						<div class="card-body table-responsive p-0">
                             <p id="tor" style="display:none"><img src="../public/storage/settings/service.PNG" style="width: 900px;"></p>
                        </div>
                    </div>
					
                        
                       
                    
                </div><br>
                </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
@push('scripts')
<script>
function myFunction() {
  var checkBox = document.getElementById("myCheck");
  var text = document.getElementById("text");
  if (checkBox.checked == true){
    text.style.display = "block";
  } else {
     text.style.display = "none";
  }
}
</script>
<script>
function Function() {
  var checkBox = document.getElementById("Check");
  var text = document.getElementById("textor");
  if (checkBox.checked == true){
    text.style.display = "block";
  } else {
     text.style.display = "none";
  }
}
</script>
<script>
function Functionchk() {
  var checkBox = document.getElementById("Chk");
  var text = document.getElementById("txt");
  if (checkBox.checked == true){
    text.style.display = "block";
  } else {
     text.style.display = "none";
  }
}
</script>
<script>
function Functionck() {
  var checkBox = document.getElementById("Ck");
  var text = document.getElementById("tor");
  if (checkBox.checked == true){
    text.style.display = "block";
  } else {
     text.style.display = "none";
  }
}
</script>
@endpush