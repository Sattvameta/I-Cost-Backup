@extends('user::layouts.master')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
@media printDiv {
  body * {
    visibility: hidden;
  }
  #invoice,
  #invoice * {
    visibility: visible;
  }
  #invoice {
    position: absolute;
    left: 0;
    top: 0;
  }
}
</style>

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
           
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('purchase.orders.index') }}"><i class="fa fa-dashboard"></i> Purchase Orders</a></li>
                    <li class="breadcrumb-item active">Purchase Order</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="card">
        {{ Form::open(['route' => ['purchase.orders.send.invoice', $purchase->id], 'method' => 'post']) }}
        
                 <div class="card-header">
         <h3 class="card-title">Purchase Order</h3><br><br>
                 </div>
        
        
        <div class="card-body">

                {{ Form::submit('Send mail', [ 'class' => "mb-2 mr-2 btn-icon-vertical btn btn-success" ]) }}
                        <a href="javascript:;" class="mb-2 mr-2 btn-icon-vertical btn btn-warning" onclick="printDiv('invoice')">Print</a>
                        <a href="{{ route('purchase.orders.index') }}" class="mb-2 mr-2 btn-icon-vertical btn btn-info">Back</a>

     </div>
          
            <div class="card-body invoice" id="invoice">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-active">
                            <tr>
							
                                <td width="36%">
								@if(@$purchase->project->company->company_logo != '' )
								<img src='../../../storage/app/public/{{ @$purchase->project->company->company_logo}}' alt="" height="50px" width="50px">
								 @endif
                                    <address>
									
                                        <strong>Company :</strong>{{ @$purchase->project->company->company_name }}<br/>
                                        <strong>Phone :</strong> {{ @$purchase->project->company->phone }}<br/>
                                        <strong>Address :</strong> : {{ @$purchase->project->company->address_line1 }}, 
                                        <br>{{ @$purchase->project->company->address_line2 }}
                                    </address>
                                </td>
                                <td width="32%">
                                    <address class="text-right">
                                        <strong>Supplier : </strong> {{ @$purchase->supplier->supplier_name }}<br/>
                                        <strong>Phone :</strong> {{ @$purchase->supplier->phone }}<br/>
                                        <strong>Address :</strong> {{ @$purchase->supplier->address_line1 }}
                                                    {!! @$purchase->supplier->address_line2 ? ', '.@$purchase->supplier->address_line2 : '' !!}
                                                    {!! @$purchase->supplier->suburb ? ', '.@$purchase->supplier->suburb : '' !!}
                                                    {!! @$purchase->supplier->postcode ? ', '.@$purchase->supplier->postcode : '' !!}
                                        <br/>
                                        <strong>Project Code :</strong> {{ @$purchase->project->unique_reference_no }}
                                    </address>
                                </td>
                                <td width="32%">
                                    <p class="text-right">
                                        <strong>Order No : </strong> {{ $purchase->project->unique_reference_no }}-{{ str_pad($purchase->purchase_no, 3, '0', STR_PAD_LEFT) }}<br/>
                                        <strong>Order Date :</strong> {{ $purchase->created_at->format('d-m-Y') }}<br/>
                                        <strong>Delivery Date :</strong> {{ $purchase->delivery_date->format('d-m-Y') }}<br/>
                                        <strong>Location :</strong> {{ $purchase->delivery_address }}
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <table class="table table-bordered table-warning"> 
                            <tbody>
                                <tr class="text-center">
                                    <td> 
                                        <div class="clk">Please : &nbsp;<strong>Supply Only</strong> 
										<a href="javascript:void"><i class="fa fa-square-o"></i></a><div>
                                    </td>
                                    <td>
                                        <div class="clk"><strong>Supply & Deliver</strong>
                                        <a href="javascript:void"><i class="fa fa-square-o"></i></a><div>
                                    </td>
                                    <td>
                                        <div class="clk"><strong>Supply & Install</strong> 
                                       <a href="javascript:void"><i class="fa fa-square-o"></i></a><div>the following:
										
                                    </td>
                                </tr> 
                            </tbody>
                        </table>
                    </div>
                </div>
              
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr class="table-success">
                                    <th width="10%">Sr No.</th>
                                    <th width="40%">Description</th>
                                    <th width="10%">Unit</th>
                                    <th width="15%">Quantity</th>
                                    <th width="15%">Rate</th>
                                    <th width="15%">Sub-total</th>
                                </tr>
                            </thead>
                            @if($purchase->orders->isNotEmpty())
                                @foreach($purchase->orders as $order)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            {{ $order->activity }}
                                        </td>
                                        <td>
                                            {{ $order->unit }}
                                        </td>
                                        <td>
                                            {{ number_format((float)$order->quantity, 4) }}
                                        </td>
                                        <td>
                                            &pound;{{ number_format((float)$order->rate, 4) }}
                                        </td>
                                        <td>
                                            &pound;{{ number_format((float)$order->total, 4) }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            <tr>
                                <td colspan="5" class="text-right"><strong>Sub Total:</strong></td>
                                <td class="text-right">&pound;{{ number_format((float)$purchase->orders->sum('total'), 4) }}</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-right"><strong>Carriage costs:</strong></td>
                                <td class="text-right">&pound;{{ number_format((float)$purchase->carriage_costs, 4) }}</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-right"><strong>C of C:</strong></td>
                                <td class="text-right">&pound;{{ number_format((float)$purchase->c_of_c, 4) }}</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-right"><strong>Other costs:</strong></td>
                                <td class="text-right">&pound;{{ number_format((float)$purchase->other_costs, 4) }}</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-right"><strong>Grand Total:</strong></td>
                                <td class="text-right">&pound;{{ number_format((float)$purchase->grand_total, 4) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="callout callout-info">
                            <strong>Important Note:</strong>In accordance with our clients requirements please provide the following documents releative to our order and your products and send to us by email (Document control) at the time of dispatch to Document control
                        </div> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-active text-right">
                            <tr>
                                <td widht="25%">
                                    <div class="clk">Certificate of conformance required upon order 
                                    <a href="javascript:void"><i class="fa fa-square-o"></i></a><div>
                                </td>
                                <td widht="25%">
                                    <div class="clk">CE certificates 
                                    <a href="javascript:void"><i class="fa fa-square-o"></i></a><div>
                                </td>
                                <td widht="25%">
                                    <div class="clk">Welder & test certs 
                                   <a href="javascript:void"><i class="fa fa-square-o"></i></a><div>
                                </td>
                                <td widht="25%">
                                     <div class="clk">Any relevant COSHH Data required 
                                    <a href="javascript:void"><i class="fa fa-square-o"></i></a><div>
                                </td>
                            </tr>
                            <tr>
                                <td widht="25%">
                                     <div class="clk">W.P.Q.R 
                                    <a href="javascript:void"><i class="fa fa-square-o"></i></a><div>
                                </td>
                                <td widht="25%">
                                    <div class="clk">Kitemark certs 
                                    <a href="javascript:void"><i class="fa fa-square-o"></i></a><div>
                                </td>
                                <td widht="25%">
                                      <div class="clk">Glass bonding certs 
                                     <a href="javascript:void"><i class="fa fa-square-o"></i></a><div>
                                </td>
                                <td widht="25%">
                                   <div class="clk">Structural calcs <a href="javascript:void"><i class="fa fa-square-o"></i></a><div>
                                </td>
                            </tr>
                            <tr>
                                <td widht="25%">
                                     <div class="clk">Galvanising certs 
                                    <a href="javascript:void"><i class="fa fa-square-o"></i></a><div>
                                </td>
                                <td widht="25%">
                                    <div class="clk">Paint certs 
                                    <a href="javascript:void"><i class="fa fa-square-o"></i></a><div>
                                </td>
                                <td widht="25%">
                                    <div class="clk">Mill certs 
                                    <a href="javascript:void"><i class="fa fa-square-o"></i></a><div>
                                </td>
                                <td widht="25%">
                                    <div class="clk"> Copy of shipping / delivery note 
                                     <a href="javascript:void"><i class="fa fa-square-o"></i></a><div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <p><strong>Additional Notes:</strong>{{ nl2br(ucfirst($purchase->notes)) }}</p> 
                        <p><b>Please acknowledge receipt of this purchase order document.	</b></p>																				
                        <p>The above purchase order number must be stated on your delivery notes and invoices.	</p>																				
                        <p>Please submit a copy of all signed delivery notes with your invoice.	</p>
                        <p><b>Should this date not be met, {{ @$purchase->project->company->company_name }} reserves the right to pass on any liability to the manufacturer / supplier.</b></p>
                    </div>
            

                  



            </div>
        </div>
        <!-- /.card -->
    {{ Form::close() }}
</section>

@stop
@push('scripts')
    <script type="text/javascript">
        function printDiv(invoice){
			
			var printContents = document.getElementById(invoice).innerHTML;
			var originalContents = document.body.innerHTML;
      
			
			document.body.innerHTML = printContents;
            
		 
			window.print();

			document.body.innerHTML = originalContents;

		}
    </script>
	
<script>
$('.clk a').click(function(){
    $(this).find('i').toggleClass('fa fa-square-o fa fa-check')
});
</script>
@endpush