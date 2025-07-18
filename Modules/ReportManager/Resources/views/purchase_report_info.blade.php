@extends('user::layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}"><i class="fa fa-dashboard"></i> Reports</a></li>
                    <li class="breadcrumb-item active">Purchase Order Report</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    
     <div class="card-body">

                               

            <button onclick="printDiv('printable-div')" class="mb-2 mr-2 btn-icon-vertical btn btn-primary">
            <i class="pe-7s-cloud-download btn-icon-wrapper"></i>Print
            </button>
              
              <button onclick="window.location.href='{{ route('reports.index') }}'" class="mb-2 mr-2 btn-icon-vertical btn btn-info">
            <i class="pe-7s-back btn-icon-wrapper"></i>Back
            </button>
      </div>
    <!-- Default box -->
    <div class="card printable-div" id="printable-div">
        <div class="card-header">
            <h3 class="card-title">Purchase Order Report</h3>
          
        </div>
        
         
        <div class="card-body">
            <div class="table-responsive">
                <table class="table-responsive table table-hover table-striped table-bordered table-active">
                    <tr>
                        <th>Delivery Date:</th>
                        <td>{{ $purchase->delivery_date->format('d-m-Y') }}</td>
                        <th>Delivery Time:</th>
                        <td>{{ $purchase->delivery_time }}</td>
                        <th>Delivery Address:</th>
                        <td>{{ $purchase->delivery_address }}</td>
                    </tr>
                    <tr>
                        <th>Area:</th>
                        <td>{{ $purchase->mainActivity->area }}</td>
                        <th>Level:</th>
                        <td>{{ $purchase->mainActivity->level }}</td>
                        <th>Sub Code:</th>
                        <td>{{ $purchase->subActivity->sub_code }}</td>
                    </tr>
                </table>
                @if($purchase->invoices->isNotEmpty())
                <table id="invoice_table" name="invoice_table" class="table-responsive table table-bordered table-warning" >
                    <thead>
                        <tr >    
                            <th>Invoice No</th>
                            <th>Invoice Amount</th>
                            <th>Invoice Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchase->invoices as $invoice)
                            <input type="hidden" name="old_invoices[{{ $invoice->id }}][invoice_id]" value="{{ $invoice->id }}">
                            <tr>
                                <td>
                                    {{ $invoice->invoice_no }}
                                </td>
                                <td>
                                    &pound;{{ number_format($invoice->invoice_amount, 4) }}
                                </td>
                                <td>
                                    {{ $invoice->invoice_date->format('d-m-Y') }}
                                </td>
                            </tr>  
                        @endforeach
                    </tbody>
                </table>
                @endif
                <table class="table-responsive table table-bordered report-table" id="purchase-report-table">
                    <thead>
                        <tr class="table-success">
                            <th>Sr No.</th>
                            <th>Activity</th>
                            <th>Images</th>
                            <th>Unit</th>
                            <th>Quantity</th>
                            <th>Rate</th>
                            <th>Estimate</th>
                            <th>Purchases</th> 	 
                            <th>Profit</th>
                            <th>Loss</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                            $grand_total = 0;
                            $total_purchases = 0;
                            $total_estimate = 0;
                            $total_profit = 0;
                            $total_loss = 0;
                        @endphp
                        @if($purchase->orders->isNotEmpty())
                            @foreach($purchase->orders as $order)
                                @php
                                    $estimate = 0;
                                    $purchases = 0;
                                    $profit1 = 0;
                                    $profit = 0;
                                    $loss1 = 0;
                                    $loss = 0;

                                    $estimate =  ((($order->activityOfOrder->rate * $order->activityOfOrder->quantity) * $purchase->subActivity->quantity) * $purchase->mainActivity->quantity);
                                    $purchases =  $order->total;
                                    $profit1 = ($estimate - $purchases);
                                    if($profit1 > 0){
                                        $profit = $profit1;
                                    }
                                    $loss1 = ($purchases - $estimate);
                                    if($loss1 > 0){
                                        $loss = $loss1;
                                    }
                                @endphp
                                <tr>
                                <td>{{ $order->activityOfOrder->item_code }}</td>
                                <td>{{ $order->activityOfOrder->activity }}</td>
                                    <td> 
                                        @if(\Storage::disk('public')->has('purchases/'.$order->photo))
                                            <img src="{{ asset('storage/purchases/'.$order->photo) }}" alt="" height="50px" width="50px">
                                            <a href="{{ asset('storage/purchases/'.$order->photo) }}" download>Download</a>
                                        @else
                                            <img src="{{ asset('images/no-img-100x92.jpg') }}" alt="" height="50px" width="50px">
                                        @endif
                                    </td>
                                    <td>{{ $order->unit }}</td>
                                    <td class="text-right">{{ $order->quantity }}</td>
                                    <td class="text-right">&pound;{{ $order->rate }}</td>
                                    <td class="text-right">&pound;{{ number_format($estimate,4) }}</td>
                                    <td class="text-right">&pound;{{ number_format($purchases,4) }}</td>
                                    <td class="text-right text-success">
                                        @if($profit > 0)
                                            &pound;{{ number_format($profit,4) }}
                                        @endif
                                    </td>
                                    <td class="text-right text-danger">
                                        @if($loss > 0)
                                            &pound;{{ number_format($loss,4) }}
                                        @endif
                                    </td>
                                </tr>
                                @php
                                    $total_estimate = ($total_estimate + $estimate);
                                    $total_purchases = ($total_purchases + $purchases);
                                    $total_profit = ($total_profit + $profit);
                                    $total_loss = ($total_loss + $loss);
                                    $grand_total = ($grand_total + $order->total);
                                    $grand_total = ($grand_total +	$purchase->carriage_costs + $purchase->c_of_c + $purchase->other_costs);
                                @endphp
                            @endforeach
                            @php
                                $grand_total = ($grand_total +	$purchase->carriage_costs + $purchase->c_of_c + $purchase->other_costs);
                            @endphp
                        @endif
                        <tfoot>
                            <tr>
                                <td colspan="6" class="text-right">Total</td>
                                <td class="text-right">&pound;{{ number_format($total_estimate , 4 ) }}</td>
                                <td class="text-right">&pound;{{ number_format($total_purchases , 4) }}</td>
                                <td class="text-right text-success">
                                    @if($total_profit > 0)
                                        &pound;{{ number_format($total_profit , 4 ) }}
                                    @endif
                                </td>
                                <td class="text-right text-danger">
                                    @if($total_loss > 0)
                                        &pound;{{ number_format($total_loss , 4 ) }}
                                    @endif
                                </td>
                            </tr>     
                            <tr class="text-right">
                                <td colspan="8">
                                    <b class="text-right">Carriage costs</b>
                                </td>
                                <td>
                                    <b>&pound;</b><b class="carriage_costs">{{ number_format($purchase->carriage_costs, 4) }}</b>
                                </td>
                            </tr>
                            <tr class="text-right">
                                <td colspan="8">
                                    <b class="text-right">C of C</b>
                                </td>
                                <td>
                                    <b>&pound;</b><b class="c_of_c">{{ number_format($purchase->c_of_c, 4) }}</b>
                                </td>
                            </tr>
                            <tr class="text-right">
                                <td colspan="8">
                                    <b class="text-right">Other costs</b>
                                </td>
                                <td>
                                    <b>&pound;</b><b class="other_costs">{{ number_format($purchase->other_costs, 4) }}</b>
                                </td>
                            </tr>
                            <tr class="text-right">
                                <td colspan="8">
                                    <b class="text-right">Total Value</b>
                                </td>
                                <td>
                                    <b>&pound;</b><b class="grand_total">{{ number_format($grand_total, 4) }}</b>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="10">
                                    <b>Note :</b><br>
                                    {{ nl2br(ucfirst($purchase->note)) }}	
                                </td>
                            </tr>
                        </tfoot>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@stop
@push('scripts')
    <script type="text/javascript">	   
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
@endpush