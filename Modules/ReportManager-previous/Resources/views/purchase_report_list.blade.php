<div class="card-header">
    <h3 class="card-title">Purchase Reports</h3>
</div>

<div class="card-body">
    <div class="table-responsive">
	<button onclick="window.location.href='{{ route('reports.export.purchase.report', $project->id) }}'"  type="button" class="mb-2 mr-2 btn-icon-vertical btn btn-info">
            <i class="pe-7s-cloud-upload btn-icon-wrapper"></i>Export
    </button>
	<div class="col-md-3">
				<div class="form-group">
						<label>Search</label><br>
							<input id="myInput" type="text">
				</div>
			</div>
			
        <table class="table-responsive table table-bordered purchase-report-datatable" id="purchase-report-datatable">
		
            <thead>
                <tr class="table-success">
                    <th>Purchase No.</th>
                    <th>Job Code</th>
                    <th>Description</th>
                    <th>PO Date</th>
                    <th>Delivery Date</th>
                    <th>Supplier Name</th>
                    <th>Invoice No</th>
                    <th>CO<sub>2</sub></th>
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
                                    <td>{{ $pPurchase->project->unique_reference_no }}-{{ str_pad($pPurchase->purchase_no, 3, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $pcode }}</td>
                                    <td>{{ $description }}</td>
									  <td>{{ date("d/m/Y", strtotime($pPurchase->created_at)) }}</td>
                                    <td>{{ date("d/m/Y", strtotime($pPurchase->delivery_date)) }}</td>
                                    <td>{{ $pPurchase->supplier->supplier_name }}</td>
                                    <td>
                                        @if($pPurchase->invoices->isNotEmpty())
                                            @foreach($pPurchase->invoices as $invoice)
                                                {{ $invoice->invoice_no }}<br />
                                            @endforeach
                                        @endif
                                    </td>
									<td>@if($pPurchase->invoices->isNotEmpty())
                                            @foreach($pPurchase->invoices as $invoice)
                                                {{ round($invoice->co2,2) }}<br />
                                            @endforeach
                                        @endif</td>
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

<script>
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#purchase-report-datatable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>