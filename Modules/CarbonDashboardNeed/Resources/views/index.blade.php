@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="panel panel-default">
                <h2>Dashboard</h2>
                <div class="panel-body">
				 <div id="chart_div" style="width: 900px; height: 500px;"></div>
                   <!-- <canvas id="canvas" height="280" width="600"></canvas>-->
                </div>
				 
            </div>
        </div>
    </div>
</div>
 <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
<script>
    var year = <?php echo $year; ?>;
    var user = <?php echo $user; ?>;
    var barChartData = {
        labels: year,
        datasets: [{
            label: 'Total Co2(t Co2 e)',
            backgroundColor: "pink",
            data: user
        }]
    };

    window.onload = function() {
        var ctx = document.getElementById("canvas").getContext("2d");
        window.myBar = new Chart(ctx, {
            type: 'bar',
            data: barChartData,
            options: {
                elements: {
                    rectangle: {
                        borderWidth: 2,
                        borderColor: '#c1c1c1',
                        borderSkipped: 'bottom'
                    }
                },
                responsive: true,
                title: {
                    display: true,
                    text: 'Carbon Calculator'
                }
            }
        });
    };
</script>
 <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawVisualization);

      function drawVisualization() {
        // Some raw data (not necessarily accurate)
		
        var data = google.visualization.arrayToDataTable([
          ['Year', 'Actual',  'Target'],
     
          [<?php echo $year; ?>, <?php echo $user; ?>, <?php echo $user; ?>],
		  [<?php echo $year_one;?> , <?php echo $user_one; ?>, <?php echo $user -5; ?>],
		  [<?php echo $year_two;?> , <?php echo $user_two; ?>, <?php echo $user -50; ?>],
	      [<?php echo $year_three;?> , <?php echo $user_three; ?>, <?php echo $user -70; ?>],
          [<?php echo $year_four;?> , <?php echo $user_four; ?>, <?php echo $user -95; ?>],
	      [<?php echo $year_five;?> , <?php echo $user_five; ?>, <?php echo $user -100; ?>],
	      [<?php echo $year_six;?> , <?php echo $user_six; ?>, <?php echo $user -110; ?>],
		  [<?php echo $year_seven;?> , <?php echo $user_seven; ?>, <?php echo $user -150; ?>],
		  [<?php echo $year_eight;?> , <?php echo $user_eight; ?>, <?php echo $user -200; ?>]
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

@endsection