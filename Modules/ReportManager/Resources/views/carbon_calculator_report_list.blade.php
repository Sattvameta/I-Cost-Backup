  
    <div class="card-body">                              

            <button onclick="printDiv('printable-div')" class="mb-2 mr-2 btn-icon-vertical btn btn-primary">
            <i class="pe-7s-print btn-icon-wrapper"></i>Print
            </button>
              
              <button onclick="window.location.href='{{ route('reports.export.carbon.calculator.report', $project->id) }}'"  type="button" class="mb-2 mr-2 btn-icon-vertical btn btn-info">
            <i class="pe-7s-cloud-upload btn-icon-wrapper"></i>Export
            </button>
    </div>
<div class="card-header">
    <h3 class="card-title">Carbon Calculator</h3>
  
    
  
</div>
<div class="card-body printable-div" id="printable-div">

    <div class="table-responsive">
	<div class="col-md-3">
				<div class="form-group">
						<label>Search</label><br>
							<input id="myInput" type="text">
				</div>
			</div>
		
        <table class="table-responsive table table-bordered report-labour-timesheet-datatable" id="report-labour-timesheet-datatable">
            <thead>
                <tr class="table-success">
                    <th>Material</th>
                    <th>Transport</th>
                    <th>Wastage</th>
                    <th>Quantity</th>
                    <th>Date</th>
                    <th>Mass</th>
                    <th>Material Factor</th>
                    <th>Transport Factor</th>
                    <th>Wastage Factor</th>
                    <th>Total Co<sub>2</sub></th>
                   
                   
                </tr>
            </thead>
			@foreach($carbon as $car)
            <tbody>
               <td>{{$car->materials}}</td>
               <td>{{$car->transport}}</td>
               <td>{{$car->wastage}}</td>
               <td>{{$car->quantity}}</td>
               <td>{{$car->created_at}}</td>
               <td>{{$car->mass}}</td>
               <td>{{$car->factors}}</td>
               <td>{{$car->transport_factor}}</td>
               <td>{{$car->wastage_factor}}</td>
               <td>{{$car->Total}}</td>
            </tbody>
			
			   @endforeach     
           <tr>
                        <td colspan="8"></td>
			                       <td>Total :</td> <td class="text-right">{{$total}}</td>
			</tr>
        </table>	
		
    </div>
</div>

<script>
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#report-labour-timesheet-datatable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>