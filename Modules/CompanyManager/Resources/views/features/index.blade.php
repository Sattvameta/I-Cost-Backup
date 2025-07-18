@extends('user::layouts.master')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('companies.index') }}"><i class="fa fa-dashboard"></i> Companies</a></li>
                    <li class="breadcrumb-item active">Features</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>


<!-- Main content -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        @include('layouts.flash.alert')
        <div class="row">
            
                <div class="card-body">

            <button onclick="window.location.href='{{ route('companies.index') }}'"  type="button" class="mb-2 mr-2 btn-icon-vertical btn btn-info">
            <i class="pe-7s-back btn-icon-wrapper"></i>Back
            </button>                       

                 </div>
    

            <!-- left column -->
            <div class="col-md-12">
                {!! Form::open(array('route' => array('companies.features.update'),'id'=>'features')) !!}
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Company : {{ $company->company_name }}</h3>
                       
                    </div>
                    <input type="hidden" name="company_id" value="{{$company->id}}">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Advance Features List</th>
                                        </tr>
                                    </thead>
                                    <tbody> 
                                        @foreach($features as $feature)
                                           @if($feature->module  != 'purchaseorders')
                                            <tr>
                                                <td>
                                                    <div class="col-md-12  permissions-col chk">
                                                        <div class="icheck-success d-inline">

                                                            <input type="checkbox" id="feature{{$feature->id}}" name="feature_id[]" value="{{$feature->id}}" {{(!in_array($feature->id, $featureIds))?'':'checked'}}>
                                                            <label for="feature{{$feature->id}}"> {{ ucwords($feature->name)}} </label> </div>
                                                        @php $privious_value = $feature->module; @endphp
                                                    </div>
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Default Features List</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($default_features as $feature)
                                            <tr>
                                                <td>
                                                    <div class="col-md-12  permissions-col chk">
                                                        <div class="icheck-success d-inline">
                                                            <label for="feature{{$feature->id}}"> {{ ucwords($feature->name)}} </label> </div>
                                                        @php $privious_value = $feature->module; @endphp
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    {{ Form::close() }}
                    <!-- /.box-footer-->
                </div>
            </div>
        </div>
    </div>
    <!-- /.box -->

</section>
<!-- /.content -->
</div>
@stop