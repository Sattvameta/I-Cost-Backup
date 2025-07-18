@extends('user::layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Formula Detail</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">

          <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{route('formulas')}}"><i class="fa fa-dashboard"></i> Formulas</a></li>
          <li class="breadcrumb-item active">View Formula</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">View Formula</h3>
      <div class="card-tools">
        <div class="box-tools pull-right">
          <a href="{{ route('formulas') }}" class="btn btn-primary">Back</a>
        </div>
      </div>
    </div>
    <div class="card-body">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <th>Project</th>
                <td>{{ $formula->project->project_title }}</td>
            </tr>
            <tr>
                <th>Keyword</th>
                <td>{{ $formula->keyword }}</td>
            </tr>
            <tr>
                <th>Description</th>
                <td>{{ $formula->description }}</td>
            </tr>
            <tr>
                <th>Formula</th>
                <td>{{ $formula->formula }}</td>
            </tr>
            <tr>
                <th>Value</th>
                <td>{{ $formula->value }}</td>
            </tr>
        </tbody>
        <table>
    </div>
    <!-- /.card -->

</section>

@stop