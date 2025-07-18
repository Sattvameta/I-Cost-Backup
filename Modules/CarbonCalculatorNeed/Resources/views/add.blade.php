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
						 
                         <form method="post" id="dynamic_form">
						 
						 <span id="result"></span>
						 <table class="table table-bordered table-striped" id="user_table">
							   <thead>
								<tr>
								    <th width="25%">Project</th>
									<th width="25%">Materials / Type</th>
									<th width="25%">Transport</th>
									<th width="25%">Wastage</th>
									<!--<th width="5%">CO<sub>2</sub></th>-->
									<th width="20%">Quantity</th>
									<!--<th width="5%">Total CO<sub>2</sub></th>-->
									<th width="3%">Action</th>
									
								</tr>
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
	
@stop
@push('scripts')

 <script>
$(document).ready(function(){

 var count = 1;

 dynamic_field(count);

 function dynamic_field(number)
 {
	
  html = '<tr>';
  html += '<td><select name="project_id[]" class="form-control select2-input"/>@foreach ($Projects as $val=>$key)<option value="{{ $val }}">{{ $key }}</option>  @endforeach</select></td>';
        html += '<td><select name="materials[]" class="form-control select2-input"/>@foreach ($allProjects as $val=>$key)<option value="{{ $key }}">{{ $val }}</option>  @endforeach</select></td>';
		  html += '<td><select name="Transport[]" class="form-control select2-input"/>@foreach ($allProjects_one as $val=>$key)<option value="{{ $key }}">{{ $val }}</option>  @endforeach</select></td>';
		    html += '<td><select name="wastage[]" class="form-control select2-input"/>@foreach ($allProjects_two as $val=>$key)<option value="{{ $key }}">{{ $val }}</option>  @endforeach</select></td>';
      //html += '<td><input type="hidden" name="co2[]" class="form-control" /></td>';
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
            url:'{{ route("store") }}',
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

 
@endpush