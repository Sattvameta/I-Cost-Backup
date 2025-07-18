@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12 offset-md-1">
            <div class="panel panel-default">
                <h2>Dashboard</h2>
				@foreach ($carbondatabase as $object)
			@if($object->carbon_database_id =="1")
		
                <div class="panel-body">
				 <div id="chart_div" style="width: 900px; height: 500px;"></div>
				
                   <!-- <canvas id="canvas" height="280" width="600"></canvas>-->
                </div>
				@endif
				
				@if($object->user_database_id =="1")
		
                <div class="panel-body">
				 <div id="chart_div" style="width: 900px; height: 500px;"></div>
                   <!-- <canvas id="canvas" height="280" width="600"></canvas>-->
                </div>
			
                   <!-- <canvas id="canvas" height="280" width="600"></canvas>-->
                </div>
				@endif
				@if($object->carbon_a_one_a_five_id =="1")
		
                <div class="panel-body">
				 <div id="chart_div" style="width: 900px; height: 500px;"></div>
		    
                   <!-- <canvas id="canvas" height="280" width="600"></canvas>-->
                </div>
				@endif
			@endforeach
				 
            </div>
        </div>
    </div>
	 
</div>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>


@foreach ($carbondatabase as $object)
			@if($object->carbon_database_id =="1")
 <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawVisualization);

      function drawVisualization() {
        // Some raw data (not necessarily accurate)
		
        var data = google.visualization.arrayToDataTable([
          ['Year', 'Actual',  'Target'],
          [<?php echo $user; ?>, <?php echo $user; ?>, <?php echo $user; ?>]
        ]);

        var options = {
          title : 'Carbon Calculator',
          vAxis: {title: 't Co2 e'},
          hAxis: {title: 'Year'},
          seriesType: 'bars',
          series: {1: {type: 'line'}}
        };

        var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
	
@endif
				
	@if($object->user_database_id =="1")
					<script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawVisualization);

      function drawVisualization() {
        // Some raw data (not necessarily accurate)
		
        var data = google.visualization.arrayToDataTable([
          ['Year', 'Actual',  'Target'],
          [<?php echo $user; ?>, <?php echo $user; ?>, <?php echo $user; ?>]
        ]);

        var options = {
          title : 'Carbon Calculator(Yearly Wise)',
          vAxis: {title: 't Co2 e'},
          hAxis: {title: 'Year'},
          seriesType: 'bars',
          series: {1: {type: 'line'}}
        };

        var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
	

	@endif
	@if($object->carbon_a_one_a_five_id =="1")
						<script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawVisualization);

      function drawVisualization() {
        // Some raw data (not necessarily accurate)
		
        var data = google.visualization.arrayToDataTable([
          ['Year', 'Actual',  'Target'],
     
          [<?php echo $user; ?>, <?php echo $user; ?>, <?php echo $user; ?>]
        ]);

        var options = {
          title : 'Carbon Calculator(Yearly Wise)',
          vAxis: {title: 't Co2 e'},
          hAxis: {title: 'Year'},
          seriesType: 'bars',
          series: {1: {type: 'line'}}
        };

        var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>

	@endif
@endforeach
@endsection