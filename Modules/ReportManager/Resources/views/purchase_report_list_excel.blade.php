<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<div class="card-header">
    <h3 class="card-title">List of Purchase for {{ $project->project_title }} </h3>
</div>

<div class="card-body">
    <div class="table-responsive">
        <table class="table-responsive table table-bordered purchase-report-datatable" id="purchase-report-datatable">
            <thead>
                <tr class="table-success">
                    <th>Purchase No.</th>
                    <th>Rev No</th>
                    <th>Job Code</th>
                    <th>Order Date/Time</th>
                    <th>Description</th>
                    <th>Delivery Address</th>
                    <th>Delivery Date</th>
                    <th>Supplier Name</th>
                    <th>Invoice No</th>
                    <th>Invoice Date</th>
                    <th>Invoice File</th>					
                    <th>CO<sub>2</sub></th>					
                    <th>Delivery Note</th>					
                    <th>Certificate</th>					
                    <th>Approval</th>					
                    <th>Status</th>					
                    <th>Inv Amount</th> 
                    <th>Price</th>
                    <th>Estimate</th>
                    <th>Profit</th>
                    <th>Loss</th>	
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
									<td>Rev {{ $pPurchase->revision_no }}</td>
                                    <td>{{ $pcode }}</td>
                                    <td>{{ $pPurchase->created_at }}</td>
                                    <td>{{ $description }}</td>
                                    <td>{{ $pPurchase->delivery_address }}</td>
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
                                  
									<td> @if($pPurchase->invoices->isNotEmpty())
                                            @foreach($pPurchase->invoices as $invoice)
                                                {{ $invoice->invoice_file }}<br />
                                            @endforeach
                                        @endif</td>
										<td> @if($pPurchase->invoices->isNotEmpty())
                                            @foreach($pPurchase->invoices as $invoice)
                                               {{ round($invoice->co2,2) }}<br />
                                            @endforeach
                                        @endif</td>
										<td> @if($pPurchase->deliverynote->isNotEmpty())
                                            @foreach($pPurchase->deliverynote as $deliverynote)
                                                {{ $deliverynote->delivery_note }}<br />
                                            @endforeach
                                        @endif</td>
										<td> @if($pPurchase->certificate->isNotEmpty())
                                            @foreach($pPurchase->certificate as $certificate)
                                                {{ $certificate->certificate }}<br />
                                            @endforeach
                                        @endif</td>
										<td> @if($pPurchase->invoices->isNotEmpty())
                                            @foreach($pPurchase->invoices as $invoice)
                                                {{ $invoice->approver_id }}<br />
                                            @endforeach
                                        @endif</td>
										<td> @if($pPurchase->statuses->isNotEmpty())
                                            @foreach($pPurchase->statuses as $statuses)
                                                {{ $statuses->status}}<br />
                                            @endforeach
                                        @endif</td>
										<td class="float-tight">
                                        @if($pPurchase->invoices->isNotEmpty())
                                            @foreach($pPurchase->invoices as $invoice)
                                             {{ number_format($invoice->invoice_amount, 2) }}<br />
                                                @php $total_inv = $total_inv + $invoice->invoice_amount; @endphp
                                            @endforeach
                                        @endif
                                    </td>
									 <td class="float-tight">
                                       {{ number_format($total_p, 2) }}
                                    </td>
                                    <td class="float-tight">
                                        @if ($trc == 1)
                                            @php $total_estimate = $total_estimate + $estimate; @endphp
                                           {{ number_format($estimate, 2) }}
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
                                                {{ number_format((float)$p, 2) }}
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
                                                {{ number_format((float)$l, 2) }}
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                   
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                    <tr class="text-right">
                        <td colspan="14">
                            <b>Totals (GBP) : </b>
                        </td>
                        <td>
                            <b> {{ number_format($total_inv, 2) }}</b>
                        </td>
                        <td>
                            <b>{{ number_format($total_hp, 2) }}</b>
                        </td>
                        <td>
                            <b>{{ number_format($total_estimate, 2) }}</b>
                        </td>
                        <td class="text-success">
                            <b>
                                @if ($total_profit >= 0)
                                    {{ number_format((float)$total_profit, 2) }}
                                @endif
                            </b>
                        </td>
                        <td class="text-danger">
                            <b>
                                @if ($total_loss >= 0)
                                {{ number_format((float)$total_loss, 2) }}
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