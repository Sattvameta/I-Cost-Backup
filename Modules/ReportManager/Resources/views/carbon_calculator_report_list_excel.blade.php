  
  
<div class="card-header">
    <h3 class="card-title">Carbon Calculator</h3>
  
    
  
</div>
<div class="card-body printable-div" id="printable-div">

    <div class="table-responsive">
	
		
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
			 <tr>
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
			    </tr>
            </tbody>
			  @endforeach     
			 <tr>
                        <td colspan="8"></td>
			                       <td>Total :</td> <td>{{$total}}</td>
			</tr>
			 
          
        </table>	
		
    </div>
</div>
