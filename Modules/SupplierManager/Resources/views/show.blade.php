@extends('user::layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}"><i class="fa fa-dashboard"></i> Suppliers</a></li>
                    <li class="breadcrumb-item active">Supplier</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    
    <div class="card-body">

                        <h5 class="card-title"></h5>
                     

                        <button onclick="window.location.href='{{ route('suppliers.index') }}'"  type="button" class="mb-2 mr-2 btn-icon-vertical btn btn-info">
                        <i class="pe-7s-back btn-icon-wrapper"></i>Back
                        </button>

                       

     </div>
    <!-- Default box -->
    <div class="card">
        
        <div class="card-body">
        <div class="table table-hover table-striped table-bordered">
            <table class="table m-0">
                <tr>
                    <th width="20%" class="table-active">Supplier Name : </th>
                    <td>{{ $supplier->supplier_name }}</td>
                </tr>
                <tr>
                    <th class="table-active">Supplier Contact Name : </th>
                    <td>{{ $supplier->supplier_contact_name }}</td>
                </tr>
                
                <tr>
                    <th class="table-active">Category : </th>
                    <td>{{ $supplier->category->name }}</td>
                </tr>
                <tr>
                    <th class="table-active">Account Name : </th>
                    <td>{{ $supplier->full_name }}</td>
                </tr>
                <tr>
                    <th class="table-active">Company : </th>
                    <td><?php if(isset($supplier->company->company_name)){ $dat = $supplier->company->company_name; }else{ $dat ="";} ?>    {{ $dat }}</td>
                </tr>
                <tr>
                    <th class="table-active">Email : </th>
                    <td>{{ $supplier->email }}</td>
                </tr>
                <tr>
                    <th class="table-active">Phone : </th>
                    <td>{{ $supplier->phone }}</td>
                </tr>
                <tr>
                    <th class="table-active">Address line1 : </th>
                    <td>{{ $supplier->address_line1 }}</td>
                </tr>
                <tr>
                    <th class="table-active">Address line2 : </th>
                    <td>{{ $supplier->address_line2 }}</td>
                </tr>
                <tr>
                    <th class="table-active">Fax : </th>
                    <td>{{ $supplier->fax }}</td>
                </tr>
                <tr>
                    <th class="table-active">Town : </th>
                    <td>{{ $supplier->suburb }}</td>
                </tr>
                <tr>
                    <th class="table-active">Post code : </th>
                    <td>{{ $supplier->postcode }}</td>
                </tr>
                <tr>
                    <th class="table-active">Status : </th>
                    <td>
                        @if($supplier->status == 1)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-danger">In-Active</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="table-active">Member since : </th>
                    <td>{{ $supplier->created_at->format('d-m-Y') }}</td>
                </tr>
            </table>
        </div>
    </div>
    <!-- /.card -->
</section>
@stop