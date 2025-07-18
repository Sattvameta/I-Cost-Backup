@extends('user::layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
     
      <div class="col-sm-12">
        <ol class="breadcrumb float-sm-right">

          <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{route('projects')}}"><i class="fa fa-dashboard"></i> Projects</a></li>
          <li class="breadcrumb-item active">Project</li>
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
        <h3 class="card-title">Project</h3><hr>
      <div class="card-tools">
        <div class="box-tools pull-right">
          <a href="{{route('projects')}}" class="btn btn-primary btn-sm">Back</a>
        </div>
      </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered">
                <tr>
                    <th width="30%" class="table-active">Default Project : </th>
                    <td>@if($project->id==$user->default_project)<span class="btn btn-success">YES</span>
                        @else<form action="{{route('projects.make.default')}}" method="POST"> @method('PATCH') @csrf <input type="hidden" value="{{ $project->id }}" name="project_id"> <button type="submit" class="btn btn-success">Set as Default</button></form>@endif</td>
                </tr>
                
                <tr>
                    <th width="30%" class="table-active">Title : </th>
                    <td>{{ $project->project_title }}</td>
                </tr>
                <tr>
                    <th class="table-active">Unique Refrence : </th>
                    <td>{{ $project->unique_reference_no }}</td>
                </tr>
                <tr>
                    <th class="table-active">Company : </th>
                    <td>{{ $project->company->company_name }}</td>
                </tr>
                <tr>
                    <th class="table-active">Client : </th>
                    <td>{{ $project->client }}</td>
                </tr>
                <tr>
                    <th class="table-active">Client Contact : </th>
                    <td>{{ $project->client_contact }}</td>
                </tr>
                <tr>
                    <th class="table-active">Client Contact : </th>
                    <td>{{ $project->client_contact }}</td>
                </tr>
                <tr>
                    <th class="table-active">Current Start Date : </th>
                    <td>{{ $project->current_start_date }}</td>
                </tr>
                <tr>
                    <th class="table-active">Current Completion Date : </th>
                    <td>{{ $project->current_completion_date }}</td>
                </tr>
                <tr>
                    <th class="table-active">Type Of Contract : </th>
                    <td>{{ $project->type_of_contract }}</td>
                </tr>
                <tr>
                    <th class="table-active">Shifts</th>
                    <td>{{ $project->shifts }}</td>
                </tr>
                <tr>
                    <th class="table-active">Project Manager</th>
                    <td>{{ $project->project_manager }}</td>
                </tr>
                <tr>
                    <th class="table-active">Site Supervisor</th>
                    <td>{{ $project->site_supervisor }}</td>
                </tr>
                <tr>
                    <th class="table-active">Location</th>
                    <td>{{ $project->location }}</td>
                </tr>
                <tr>
                    <th class="table-active">Sector</th>
                    <td>{{ $project->sector }}</td>
                </tr>
                <tr>
                    <th class="table-active">Region</th>
                    <td>{{ $project->region }}</td>
                </tr>
                <tr>
                    <th class="table-active">Address</th>
                    <td>{{ $project->project_address }}</td>
                </tr>
                <tr>
                    <th class="table-active">Current Value of Project</th>
                    <td>{{ $project->current_value_of_project }}</td>
                </tr>
                <tr>
                    <th class="table-active">Base Margin</th>
                    <td>{{ $project->base_margin }}</td>
                </tr>
                <tr>
                    <th class="table-active">Change Management</th>
                    <td>{{ $project->change_management }}</td>
                </tr>
                <tr>
                    <th class="table-active">Adjusted Contract Value</th>
                    <td>{{ $project->adjusted_contract_value }}</td>
                </tr>
                <tr>
                    <th class="table-active">Labour Value</th>
                    <td>{{ $project->labour_value }}</td>
                </tr>
                <tr>
                    <th class="table-active">Tender Status</th>
                    <td>{{ config('constants.TENDER_STATUS')[Arr::get($project, 'tender_status')] }}</td>
                </tr>
                <tr>
                    <th class="table-active">Version</th>
                    <td>{{ $project->version }}</td>
                </tr>
            </table>
        </div>
    </div>
  </div>

</section>

@stop