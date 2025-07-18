<div class="card-header" style="display:none;">
    <h3 class="card-title">Purchase Reports</h3>
</div>
<div class="card-body" style="display:none;">
    <div class="table-responsive">
        <table class="table table-bordered purchase-report-datatable" id="purchase-report-datatable">
            <thead>
                <tr class="table-success">
                    <th>Purchase No.</th>
                    <th>Project</th>
                    <th>Description</th>
                    <th>Delivery Date</th>
                    <th>Supplier Name</th>
                    <th>Invoice No</th>
                    <th>Invoice Date</th>
                    <th>Inv Amount</th> 	 
                    <th>Price</th>
                    <th>Estimate</th>
                    <th>Profit</th>
                    <th>Loss</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $po =array(); $i = 0; ?>
                @php 
                    $total_estimate = 0;
                    $total_profit = 0;
                    $total_loss = 0;
                    $total_inv = 0;
                    $total_hp = 0;
                @endphp
                @if($purchases->isNotEmpty())
                    @foreach($purchases as $purchase)
                        @php
                            $trc = 0;
                            $total_pg = 0;
                            $pQuery = $project->purchases()->where('sub_activity_id', $purchase->sub_activity_id);
                            if(isset($dates['from']) && isset($dates['to'])){
                                $pQuery->whereBetween('delivery_date', [$dates['from'], $dates['to']]);
                            }
                            $pPurchases = $pQuery->get();
                        @endphp
                        @if($pPurchases->isNotEmpty())
                            @foreach($pPurchases as $pPurchase)
                                @php 
                                    $trc = $trc + 1;
                                    $estimate = 0;
                                    $purchases = 0;
                                    $profit1 = 0;
                                    $profit = 0;
                                    $loss1 = 0;
                                    $loss = 0;
                                    $total_p = 0;
                                    $g_total = 0;
                                    $p_total = 0;
                                    $a = 0;
                                    $p = 0;
                                    $l = 0; 
                                    $p1 = 0;
                                    $l1 = 0;
                                    $pcode = "";
                                    $description = "";
                                    $total_pg = ($total_pg + $pPurchase->orders->sum('total'));
                                    $total_pg = ($total_pg + $pPurchase->carriage_costs + $pPurchase->c_of_c + $pPurchase->other_costs);
                                @endphp
                                @foreach($pPurchase->orders as $order)
                                    @php
                                        $total_p = ($total_p + $order->total);
                                        $description = strstr($order->activityOfOrder->activity, '-', true);
                                        $a = $a + 1;
                                        if ($a > 1) {
                                            $description = "-";
                                        }
                                        $estimate =  $estimate + ((($order->activityOfOrder->rate * $order->activityOfOrder->quantity) * $pPurchase->subActivity->quantity) * $pPurchase->mainActivity->quantity);
                                        $pcode = $order->activityOfOrder->item_code;
                                    @endphp
                                @endforeach
                                @php 
                                    $total_p = ($total_p + $pPurchase->carriage_costs + $pPurchase->c_of_c + $pPurchase->other_costs);	
                                    $purchases = $total_p;
                                    $profit1 = ($estimate - $purchases);
                                    if($profit1 > 0){
                                        $profit = $profit1;
                                    }
                                    $loss1 = ($purchases - $estimate);
                                    if($loss1 > 0){
                                        $loss = $loss1;
                                    }  
                                    $total_hp = ($total_hp+$total_p);
                                @endphp
                                <tr>
                                    <?php  
                                   
                                    $datas[$i]['unique_reference_no']= str_pad($pPurchase->purchase_no, 3, '0', STR_PAD_LEFT);
                                    $datas[$i]['date']= date("Y-m-d", strtotime($pPurchase->delivery_date));
                                    $datas[$i]['price']= $total_p;
                                    
                                    if($trc == 1){
                                        $_est = $estimate;
                                    }else{
                                        $_est = 0;
                                    }
                                   
                                          
                                        
                                    $datas[$i]['estimate']= $_est;
                                    $i++;
                                    $po =$datas;
                                    ?>
                                    <td>{{ $pPurchase->project->unique_reference_no }}-{{ str_pad($pPurchase->purchase_no, 3, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $pcode }}</td>
                                    <td>{{ $description }}</td>
                                    <td>{{ date("d/m/Y", strtotime($pPurchase->delivery_date)) }}</td>
                                    <td>{{ $pPurchase->supplier->supplier_name }}</td>
                                    <td>
                                        @if($pPurchase->invoices->isNotEmpty())
                                            @foreach($pPurchase->invoices as $invoice)
                                                {{ $invoice->invoice_no }}<br />
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        @if($pPurchase->invoices->isNotEmpty())
                                            @foreach($pPurchase->invoices as $invoice)
                                                {{ date("d-m-Y", strtotime($invoice->invoice_date)) }}<br />
                                            @endforeach
                                        @endif
                                    </td>
                                    <td class="float-tight">
                                        @if($pPurchase->invoices->isNotEmpty())
                                            @foreach($pPurchase->invoices as $invoice)
                                                &pound;{{ number_format($invoice->invoice_amount, 2) }}<br />
                                                @php $total_inv = $total_inv + $invoice->invoice_amount; @endphp
                                            @endforeach
                                        @endif
                                    </td>
                                    <td class="float-tight">
                                        &pound;{{ number_format($total_p, 2) }}
                                    </td>
                                    <td class="float-tight">
                                        @if ($trc == 1)
                                            @php $total_estimate = $total_estimate + $estimate; @endphp
                                            &pound;{{ number_format($estimate, 2) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-success float-tight">
                                        @if ($trc == 1)
                                            @php $p1 = $estimate - $total_pg; @endphp
                                            @if ($p1 > 0)
                                                @php $p = $p1; @endphp
                                            @endif
                                            @php $total_profit = $total_profit + $p; @endphp
                                            @if ($p >= 0)
                                                &pound; {{ number_format((float)$p, 2) }}
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-danger float-tight">
                                        @if ($trc == 1)
                                            @php $l1 = $total_pg - $estimate; @endphp
                                            @if ($l1 > 0)
                                                @php $l = $l1; @endphp
                                            @endif
                                            @php $total_loss = $total_loss + $l; @endphp
                                            @if ($l >= 0)
                                                &pound; {{ number_format((float)$l, 2) }}
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('reports.view.purchase.report', array_merge([$pPurchase->id], $dates)) }}" class='btn btn-info btn-sm' title='Click here to view or print this'><i class='fas fa-eye'></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                    <tr class="text-right">
                        <td colspan="7">
                            <b>Totals : </b>
                        </td>
                        <td>
                            <b> &pound;{{ number_format($total_inv, 2) }}</b>
                        </td>
                        <td>
                            <b> &pound;{{ number_format($total_hp, 2) }}</b>
                        </td>
                        <td>
                            <b> &pound;{{ number_format($total_estimate, 2) }}</b>
                        </td>
                        <td class="text-success">
                            <b> 
                                @if ($total_profit >= 0)
                                    &pound;{{ number_format((float)$total_profit, 2) }}
                                @endif
                            </b>
                        </td>
                        <td class="text-danger">
                            <b>
                                @if ($total_loss >= 0)
                                    &pound;{{ number_format((float)$total_loss, 2) }}
                                @endif
                            </b>
                        </td>
                        <td></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
<?php 


$f_a=array();
$new =array();
foreach ($po as $key=>$date_str) {
        //print_r($key);
   // print_r($date_str);
     $current_year = date('Y');
     $s_date = date('Y', strtotime($date_str['date']));
 
    if($current_year != $s_date){
      unset($po[$key]);  
    }else{
        
    $s_month = date('m', strtotime($date_str['date']));
    $new[$s_month][$key]['estimate'] = $date_str['estimate'];
    $new[$s_month][$key]['price'] = $date_str['price'];
    
    }
}

$final=array();
foreach($new as $o=>$k){
    unset($est);
    unset($prc);
    foreach($k as $da){
        //print_r($o);
        foreach($da as $y=>$t){
            if($y == "estimate"){
                $est[] = $t;
            }elseif($y == "price"){
                $prc[] = $t;
            }
        } 
        $final[(int)$o]['es'] =array_sum($est);
        $final[(int)$o]['pr'] =array_sum($prc);
    }
}




//print_r($final);
?>




        
      
<html>
  <head>
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawVisualizationr);
        
        function drawVisualizationr() {
        // Some raw data (not necessarily accurate)
        var data = google.visualization.arrayToDataTable([
        ['Project', 'Actuals', 'Budget', 'Average'],
        
      
        
        <?php for($i=1;$i<=12;$i++){ $cur_year = $i."/".date('Y'); ?>
        
        <?php if (in_array($i,array_keys($final))){ ?>
            
         ['<?php  echo $cur_year;  ?>',  <?php  echo $final[$i]['pr'];  ?>,<?php  echo $final[$i]['es'];  ?>,<?php $bal = $final[$i]['es'] - $final[$i]['pr']; echo $bal;  ?>],
         
       
            
       <?php } else{ ?>
        
        ["<?php echo $cur_year; ?>",0,0,0],
        
        <?php } ?>
        
            
        
        <?php } ?>
        
        ]);
        
        
      
            
        
     
      
      
        
        var options = {
        title : '',
        vAxis: {title: ''},
        hAxis: {title: ''},
        seriesType: 'bars',
        height: 400,
        width: 1200,
        
        series: {2: {type: 'line'}}
        };
        
        var chart = new google.visualization.ComboChart(document.getElementById('estimate_purchase6'));
        chart.draw(data, options);
        }
    </script>
  </head>

  <body>
   
                
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Estimate Vs Purchase
                                <small> </small>
                            </h4>
                        </div>
                        <div class="card-body ct-chart table-responsive p-0" id="estimate_purchase6"></div>
                        <div class="card-footer">
                           
                        </div>
                    </div>
              
  </body>
</html>           
