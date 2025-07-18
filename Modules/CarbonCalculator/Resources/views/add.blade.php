@extends('user::layouts.masterlist')
@section('content')
<head>
<meta charset="utf-8">
<meta name="csrf-token" content="content">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
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
						 
                         <form method="post" id="dynamic_form">
						 
						 <span id="result"></span>
						 <table class="table table-bordered table-striped" id="user_table">
							   <thead>
							       	@foreach ($carbondatabase as $object)
		                        	@if($object->carbon_a_one_a_five_id =="1" || $object->user_database_id == "1")
								<tr>
								   	<th width="25%">Project</th>
									<th width="25%">Materials / Type</th>
										<th width="25%">Transport</th>
									<th width="5%">Wastage</th>
										<th width="10%">Unit</th>
									<th width="5%">Quantity</th>
								
									<th width="3%">Action</th>
									</tr>
								@elseif($object->ghg_id =="1")
								     		<tr>
								   	<th width="25%">Project</th>
									<th width="25%">Source</th>
									<th width="25%">Category</th>
										<th width="20%">Unit</th>
								    <!--<th width="5%">CO<sub>2</sub></th>-->
									<th width="5%">Quantity</th>
									<!--<th width="5%">Total CO<sub>2</sub></th>-->
									<th width="3%">Action</th>
									</tr>
										@else
											<tr>
								   	<th width="25%">Project</th>
									<th width="25%">Source</th>
									<th width="20%">Unit</th>
									<!--<th width="5%">CO<sub>2</sub></th>-->
									<th width="20%">Quantity</th>
									
									<!--<th width="5%">Total CO<sub>2</sub></th>-->
									<th width="3%">Action</th>
									</tr>
										@endif
								     	@endforeach
							   </thead>
					   <tbody>

					   </tbody>
					   <tfoot>
						<tr>
										<td colspan="5" align="right">&nbsp;</td>
										<td>
						  @csrf
						  <input type="submit" name="save" id="save" class="btn btn-primary" value="Save" />
						 </td>
						</tr>
					   </tfoot>
					</table>
                </form>
                   
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- <section class="content">
        @include('layouts.flash.alert')
		
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
					
                        <div class="row">
                            <div class="col-md-3">
						
                                    
                                </div>
                            </div>
                        </div>
					
                         <table  class="table table-bordered table-hover" id="doc-datatable" data-table="project">
                            <thead>
                                <tr>
								 
                            <th width="5%">Materials/Type</th>
							<th width="5%">Transport</sub></th>
							<th width="5%">Wastage</th>
							<th width="5%">Quantity</th>
							<th width="5%">Total CO<sub>2</sub></th>
							<th width="5%">Date /Time</th>
							<th width="5%">Action</th>
							
                                </tr>
                            </thead>
                        </table>
                     </form>
                    </div>
                </div>
            </div>
        </div>
    </section>-->
	
@stop
@push('scripts')
	@foreach ($carbondatabase as $object)
		                        	@if($object->carbon_a_one_a_five_id =="1" || $object->user_database_id == "1")
 <script>
$(document).ready(function(){

 var count = 1;

 dynamic_field(count);

 function dynamic_field(number)
 {
  html = '<tr>';
  html += '<td><select name="project_id[]" class="form-control select2-input"/>@foreach ($project as $pr)<option value="{{$pr-> id}}">{{$pr-> project_title}}(v -{{$pr-> version}})</option>@endforeach @foreach ($Projects as $val=>$key)<option value="{{ $val }}">{{ $key }}</option>  @endforeach</select></td>';
        html += '<td><select name="materials[]" class="form-control select2-input"/>@foreach ($allProjects as $val=>$key)<option value="{{$key }}">{{ $val }}</option>  @endforeach</select></td>';
	   html += '<td><select name="Transport[]" class="form-control select2-input"/>@foreach ($allProjects_one as $val=>$key)<option value="{{ $key }}">{{ $val }}</option>  @endforeach</select></td>';
		   html += '<td><select name="wastage[]" class="form-control select2-input" style="width: 92px;"/>@foreach ($allProjects_two as $val=>$key)<option value="{{ $key }}">{{ $val }}</option>  @endforeach</select></td>';
           html += '<td><input type="text" name="co2[]" class="form-control" placeholder="Tonne" disabled/></td>';
          html += '<td><input type="text" name="quantity[]" class="form-control" style="width: 90px;"/></td>';
        // html += '<td><input type="hidden" name="total[]" class="form-control"/></td>'; 
        if(number > 1)
        {
            html += '<td><button type="button" name="remove" id="" class="btn btn-danger remove">Remove</button></td></tr>';
            $('tbody').append(html);
        }
        else
        {   
            html += '<td><button type="button" name="add" id="add" class="btn btn-success">Add</button></td></tr>';
            $('tbody').html(html);
        }
 }

 $(document).on('click', '#add', function(){
  count++;
  dynamic_field(count);
 });

 $(document).on('click', '.remove', function(){
  count--;
  $(this).closest("tr").remove();
 });
 $('#dynamic_form').on('submit', function(event){
        event.preventDefault();
        $.ajax({
            url:'{{ route("store.ajax") }}',
            method:'post',
            data:$(this).serialize(),
            dataType:'json',
            beforeSend:function(){
                $('#save').attr('disabled','disabled');
            },
            success:function(data)
			
            {
                if(data.error)
                {
                    var error_html = '';
                    for(var count = 0; count < data.error.length; count++)
                    {
                        error_html += '<p>'+data.error[count]+'</p>';
                    }
                    $('#result').html('<div class="alert alert-danger">'+error_html+'</div>');
                }
                else
                {
                    dynamic_field(1);
                    $('#result').html('<div class="alert alert-success">'+data.success+'</div>');
                }
                $('#save').attr('disabled', false);
            }
        })
 });


});
</script>  
	@elseif($object->ghg_id =="1")
	
<script>
$(document).ready(function(){

 var count = 1;

 dynamic_field(count);

 function dynamic_field(number)
 { 
    
  html = '<tr>';
  html += '<td><select name="project_id[]" class="form-control select2-input"/>@foreach ($project as $pr)<option value="{{$pr-> id}}">{{$pr-> project_title}}(v -{{$pr-> version}})</option>@endforeach  @foreach ($Projects as $val=>$key)<option value="{{ $val }}">{{ $key }}</option>  @endforeach</select></td>';
        html += '<td><select name="source[]" id="country-dropdown'+count+'" class="form-control select2-input"/>@foreach ($allProjects as $val=>$key)<option value="{{$key }}">{{ $val }}</option>  @endforeach</select></td>';
	     html += '<td><select name="materials[]" id="state-dropdown'+count+'" class="form-control select2-input"/></select></td>';
	     
	      html += '<td><select name="unit[]" id="city-dropdown'+count+'" class="form-control select2-input" disabled/></select></td>';
	     
          // html += '<td><input type="hidden" name="co2[]" class="form-control" /></td>';
          html += '<td><input type="text" name="quantity[]" class="form-control" /></td>';
        // html += '<td><input type="hidden" name="total[]" class="form-control"/></td>';  
        if(number > 1)
        {
            html += '<td><button type="button" name="remove" id="" class="btn btn-danger remove">Remove</button></td></tr>';
            $('tbody').append(html);
        }
        else
        {   
            html += '<td><button type="button" name="add" id="add" class="btn btn-success">Add</button></td></tr>';
            $('tbody').html(html);
        }
        
/*$('#country-dropdown'+count+'').on('change', function () {
var mass = this.value;
$('#state-dropdown'+count+'').html('');
$.ajax({
url:'{{ route("get.states.by.country") }}',
method:'post',
data: {
mass: mass,
_token: '{{csrf_token()}}' ,
},
dataType : 'json',
success: function (results){
 
$('#state-dropdown'+count+'').html('<option value="">Select Category</option>'); 
$.each(results.states,function(key,value){
$('#state-dropdown'+count+'').append('<option value="'+value.id+'">'+value.carbondatabase_id+'</option>');
});
//$('#city-dropdown').html('<option value="">Select State First</option>'); 
}
});
});  */   
$('#country-dropdown1').on('change', function () {
var mass = this.value;
$('#state-dropdown1').html('');
$.ajax({
url:'{{ route("get.states.by.country") }}',
method:'post',
data: {
mass: mass,
_token: '{{csrf_token()}}' ,
},
dataType : 'json',
success: function (results){
 
$('#state-dropdown1').html('<option value="">Select Category</option>'); 
$.each(results.states,function(key,value){
$('#state-dropdown1').append('<option value="'+value.id+'">'+value.carbondatabase_id+'</option>');
});
//$('#city-dropdown').html('<option value="">Select State First</option>'); 
}
});
});   
$('#state-dropdown1').on('change', function() {
var id = this.value;
$("#city-dropdown1").html('');
$.ajax({
url:'{{ route("get.cities.by.state") }}',
type: "POST",
data: {
id: id,
_token: '{{csrf_token()}}' 
},
dataType : 'json',
success: function(result){
$.each(result.cities,function(key,value){
$("#city-dropdown1").append('<option value="'+value.id+'">'+value.unit+'</option>');
});
}
});
});

$('#country-dropdown2').on('change', function () {
var mass = this.value;
$('#state-dropdown2').html('');
$.ajax({
url:'{{ route("get.states.by.country") }}',
method:'post',
data: {
mass: mass,
_token: '{{csrf_token()}}' ,
},
dataType : 'json',
success: function (results){
 
$('#state-dropdown2').html('<option value="">Select Category</option>'); 
$.each(results.states,function(key,value){
$('#state-dropdown2').append('<option value="'+value.id+'">'+value.carbondatabase_id+'</option>');
});
//$('#city-dropdown').html('<option value="">Select State First</option>'); 
}
});
});   
$('#state-dropdown2').on('change', function() {
var id = this.value;
$("#city-dropdown2").html('');
$.ajax({
url:'{{ route("get.cities.by.state") }}',
type: "POST",
data: {
id: id,
_token: '{{csrf_token()}}' 
},
dataType : 'json',
success: function(result){
$.each(result.cities,function(key,value){
$("#city-dropdown2").append('<option value="'+value.id+'">'+value.unit+'</option>');
});
}
});
});
$('#country-dropdown3').on('change', function () {
var mass = this.value;
$('#state-dropdown3').html('');
$.ajax({
url:'{{ route("get.states.by.country") }}',
method:'post',
data: {
mass: mass,
_token: '{{csrf_token()}}' ,
},
dataType : 'json',
success: function (results){
 
$('#state-dropdown3').html('<option value="">Select Category</option>'); 
$.each(results.states,function(key,value){
$('#state-dropdown3').append('<option value="'+value.id+'">'+value.carbondatabase_id+'</option>');
});
//$('#city-dropdown').html('<option value="">Select State First</option>'); 
}
});
});
$('#state-dropdown3').on('change', function() {
var id = this.value;
$("#city-dropdown3").html('');
$.ajax({
url:'{{ route("get.cities.by.state") }}',
type: "POST",
data: {
id: id,
_token: '{{csrf_token()}}' 
},
dataType : 'json',
success: function(result){
$.each(result.cities,function(key,value){
$("#city-dropdown3").append('<option value="'+value.id+'">'+value.unit+'</option>');
});
}
});
});
$('#country-dropdown4').on('change', function () {
var mass = this.value;
$('#state-dropdown4').html('');
$.ajax({
url:'{{ route("get.states.by.country") }}',
method:'post',
data: {
mass: mass,
_token: '{{csrf_token()}}' ,
},
dataType : 'json',
success: function (results){
 
$('#state-dropdown4').html('<option value="">Select Category</option>'); 
$.each(results.states,function(key,value){
$('#state-dropdown4').append('<option value="'+value.id+'">'+value.carbondatabase_id+'</option>');
});
//$('#city-dropdown').html('<option value="">Select State First</option>'); 
}
});
});
$('#state-dropdown4').on('change', function() {
var id = this.value;
$("#city-dropdown4").html('');
$.ajax({
url:'{{ route("get.cities.by.state") }}',
type: "POST",
data: {
id: id,
_token: '{{csrf_token()}}' 
},
dataType : 'json',
success: function(result){
$.each(result.cities,function(key,value){
$("#city-dropdown4").append('<option value="'+value.id+'">'+value.unit+'</option>');
});
}
});
});
$('#country-dropdown5').on('change', function () {
var mass = this.value;
$('#state-dropdown5').html('');
$.ajax({
url:'{{ route("get.states.by.country") }}',
method:'post',
data: {
mass: mass,
_token: '{{csrf_token()}}' ,
},
dataType : 'json',
success: function (results){
 
$('#state-dropdown5').html('<option value="">Select Category</option>'); 
$.each(results.states,function(key,value){
$('#state-dropdown5').append('<option value="'+value.id+'">'+value.carbondatabase_id+'</option>');
});
//$('#city-dropdown').html('<option value="">Select State First</option>'); 
}
});
});
$('#state-dropdown5').on('change', function() {
var id = this.value;
$("#city-dropdown5").html('');
$.ajax({
url:'{{ route("get.cities.by.state") }}',
type: "POST",
data: {
id: id,
_token: '{{csrf_token()}}' 
},
dataType : 'json',
success: function(result){
$.each(result.cities,function(key,value){
$("#city-dropdown5").append('<option value="'+value.id+'">'+value.unit+'</option>');
});
}
});
});
$('#country-dropdown6').on('change', function () {
var mass = this.value;
$('#state-dropdown6').html('');
$.ajax({
url:'{{ route("get.states.by.country") }}',
method:'post',
data: {
mass: mass,
_token: '{{csrf_token()}}' ,
},
dataType : 'json',
success: function (results){
 
$('#state-dropdown6').html('<option value="">Select Category</option>'); 
$.each(results.states,function(key,value){
$('#state-dropdown6').append('<option value="'+value.id+'">'+value.carbondatabase_id+'</option>');
});
//$('#city-dropdown').html('<option value="">Select State First</option>'); 
}
});
}); 
$('#state-dropdown6').on('change', function() {
var id = this.value;
$("#city-dropdown6").html('');
$.ajax({
url:'{{ route("get.cities.by.state") }}',
type: "POST",
data: {
id: id,
_token: '{{csrf_token()}}' 
},
dataType : 'json',
success: function(result){
$.each(result.cities,function(key,value){
$("#city-dropdown6").append('<option value="'+value.id+'">'+value.unit+'</option>');
});
}
});
});
$('#country-dropdown7').on('change', function () {
var mass = this.value;
$('#state-dropdown7').html('');
$.ajax({
url:'{{ route("get.states.by.country") }}',
method:'post',
data: {
mass: mass,
_token: '{{csrf_token()}}' ,
},
dataType : 'json',
success: function (results){
 
$('#state-dropdown7').html('<option value="">Select Category</option>'); 
$.each(results.states,function(key,value){
$('#state-dropdown7').append('<option value="'+value.id+'">'+value.carbondatabase_id+'</option>');
});
//$('#city-dropdown').html('<option value="">Select State First</option>'); 
}
});
}); 
$('#state-dropdown7').on('change', function() {
var id = this.value;
$("#city-dropdown7").html('');
$.ajax({
url:'{{ route("get.cities.by.state") }}',
type: "POST",
data: {
id: id,
_token: '{{csrf_token()}}' 
},
dataType : 'json',
success: function(result){
$.each(result.cities,function(key,value){
$("#city-dropdown7").append('<option value="'+value.id+'">'+value.unit+'</option>');
});
}
});
});
$('#country-dropdown8').on('change', function () {
var mass = this.value;
$('#state-dropdown8').html('');
$.ajax({
url:'{{ route("get.states.by.country") }}',
method:'post',
data: {
mass: mass,
_token: '{{csrf_token()}}' ,
},
dataType : 'json',
success: function (results){
 
$('#state-dropdown8').html('<option value="">Select Category</option>'); 
$.each(results.states,function(key,value){
$('#state-dropdown8').append('<option value="'+value.id+'">'+value.carbondatabase_id+'</option>');
});
//$('#city-dropdown').html('<option value="">Select State First</option>'); 
}
});
}); 
$('#state-dropdown8').on('change', function() {
var id = this.value;
$("#city-dropdown8").html('');
$.ajax({
url:'{{ route("get.cities.by.state") }}',
type: "POST",
data: {
id: id,
_token: '{{csrf_token()}}' 
},
dataType : 'json',
success: function(result){
$.each(result.cities,function(key,value){
$("#city-dropdown8").append('<option value="'+value.id+'">'+value.unit+'</option>');
});
}
});
});
$('#country-dropdown9').on('change', function () {
var mass = this.value;
$('#state-dropdown9').html('');
$.ajax({
url:'{{ route("get.states.by.country") }}',
method:'post',
data: {
mass: mass,
_token: '{{csrf_token()}}' ,
},
dataType : 'json',
success: function (results){
 
$('#state-dropdown9').html('<option value="">Select Category</option>'); 
$.each(results.states,function(key,value){
$('#state-dropdown9').append('<option value="'+value.id+'">'+value.carbondatabase_id+'</option>');
});
//$('#city-dropdown').html('<option value="">Select State First</option>'); 
}
});
}); 
$('#state-dropdown9').on('change', function() {
var id = this.value;
$("#city-dropdown9").html('');
$.ajax({
url:'{{ route("get.cities.by.state") }}',
type: "POST",
data: {
id: id,
_token: '{{csrf_token()}}' 
},
dataType : 'json',
success: function(result){
$.each(result.cities,function(key,value){
$("#city-dropdown9").append('<option value="'+value.id+'">'+value.unit+'</option>');
});
}
});
});
$('#country-dropdown10').on('change', function () {
var mass = this.value;
$('#state-dropdown10').html('');
$.ajax({
url:'{{ route("get.states.by.country") }}',
method:'post',
data: {
mass: mass,
_token: '{{csrf_token()}}' ,
},
dataType : 'json',
success: function (results){
 
$('#state-dropdown10').html('<option value="">Select Category</option>'); 
$.each(results.states,function(key,value){
$('#state-dropdown10').append('<option value="'+value.id+'">'+value.carbondatabase_id+'</option>');
});
//$('#city-dropdown').html('<option value="">Select State First</option>'); 
}
});
});
$('#state-dropdown10').on('change', function() {
var id = this.value;
$("#city-dropdown10").html('');
$.ajax({
url:'{{ route("get.cities.by.state") }}',
type: "POST",
data: {
id: id,
_token: '{{csrf_token()}}' 
},
dataType : 'json',
success: function(result){
$.each(result.cities,function(key,value){
$("#city-dropdown10").append('<option value="'+value.id+'">'+value.unit+'</option>');
});
}
});
});
 }


 $(document).on('click', '#add', function(){
  count++;
  dynamic_field(count);
 });

 $(document).on('click', '.remove', function(){
  count--;
  $(this).closest("tr").remove();
 });
  


 $('#dynamic_form').on('submit', function(event){
        event.preventDefault();
        $.ajax({
            url:'{{ route("store.ajax") }}',
            method:'post',
            data:$(this).serialize(),
            dataType:'json',
            beforeSend:function(){
                $('#save').attr('disabled','disabled');
            },
            success:function(data)
			
            {
                if(data.error)
                {
                    var error_html = '';
                    for(var count = 0; count < data.error.length; count++)
                    {
                        error_html += '<p>'+data.error[count]+'</p>';
                    }
                    $('#result').html('<div class="alert alert-danger">'+error_html+'</div>');
                }
                else
                {
                    dynamic_field(1);
                    $('#result').html('<div class="alert alert-success">'+data.success+'</div>');
                }
                $('#save').attr('disabled', false);
            }
        })
 });


});
</script>
@else
<script>
$(document).ready(function(){

 var count = 1;

 dynamic_field(count);

 function dynamic_field(number)
 {
  html = '<tr>';
  html += '<td><select name="project_id[]" class="form-control select2-input"/>@foreach ($project as $pr)<option value="{{$pr-> id}}">{{$pr-> project_title}}(v -{{$pr-> version}})</option>@endforeach @foreach ($Projects as $val=>$key)<option value="{{ $val }}">{{ $key }}</option>  @endforeach</select></td>';
        html += '<td><select name="materials[]" class="form-control select2-input"/>@foreach ($allProjects as $val=>$key)<option value="{{$key }}">{{ $val }}</option>  @endforeach</select></td>';
	      html += '<td><input type="text" name="co2[]" class="form-control" placeholder="Tonne" disabled/></td>';
          // html += '<td><input type="hidden" name="co2[]" class="form-control" /></td>';
          html += '<td><input type="text" name="quantity[]" class="form-control" /></td>';
        // html += '<td><input type="hidden" name="total[]" class="form-control"/></td>';  
        if(number > 1)
        {
            html += '<td><button type="button" name="remove" id="" class="btn btn-danger remove">Remove</button></td></tr>';
            $('tbody').append(html);
        }
        else
        {   
            html += '<td><button type="button" name="add" id="add" class="btn btn-success">Add</button></td></tr>';
            $('tbody').html(html);
        }
 }

 $(document).on('click', '#add', function(){
  count++;
  dynamic_field(count);
 });

 $(document).on('click', '.remove', function(){
  count--;
  $(this).closest("tr").remove();
 });
 $('#dynamic_form').on('submit', function(event){
        event.preventDefault();
        $.ajax({
            url:'{{ route("store.ajax") }}',
            method:'post',
            data:$(this).serialize(),
            dataType:'json',
            beforeSend:function(){
                $('#save').attr('disabled','disabled');
            },
            success:function(data)
			
            {
                if(data.error)
                {
                    var error_html = '';
                    for(var count = 0; count < data.error.length; count++)
                    {
                        error_html += '<p>'+data.error[count]+'</p>';
                    }
                    $('#result').html('<div class="alert alert-danger">'+error_html+'</div>');
                }
                else
                {
                    dynamic_field(1);
                    $('#result').html('<div class="alert alert-success">'+data.success+'</div>');
                }
                $('#save').attr('disabled', false);
            }
        })
 });


});
</script>
@endif
	@endforeach
<!--<script> 

        $(document).ready(function() {
            var t = $('#doc-datatable').DataTable({
               dom: 'Bfrtip',
             "paging": true,
                "autoWidth": false,
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "searchable": true,
                "pageLength": 25,
                "order": [[0, 'asc']],
                ajax: {
                    //url: "{{ route('formulas.ajax.list.all') }}",
					url: "{{ route('carboncalculator.ajax.list.all') }}",
                    type: 'GET',
                    data: function(d) {
						
                        //d.status_filter_id = $('#status_filter').val();
                        d.project_filter_id = $('#project_filter').val();
						 //d.search = $('input[type="search"]').val()
                    }
                },
                columns: [
                    
                   
                    {data: 'materials', name: 'materials'},
                    {data: 'transport', name: 'transport'},
                    {data: 'wastage', name: 'wastage'},
                    //{data: '', name: ''},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'total', name: 'total'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action'}
                  
                ],
                "deferRender": true,
                'columnDefs': [{
                    "targets": 'no-sort',
                    "orderable": false,
                }]
            });
            $('#project_filter').on('change', function() {
                $('#doc-datatable').DataTable().draw(true);
            });
        });
    </script>-->
 
@endpush