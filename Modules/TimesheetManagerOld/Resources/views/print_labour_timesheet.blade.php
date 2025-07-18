@extends('user::layouts.master')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      
      <div class="col-sm-12">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('timesheets.labour', $timesheet->project->id) }}">Timesheets</a></li>
          <li class="breadcrumb-item active">Print Timesheet</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content printable_div">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Timesheet</h3><hr>
            <div class="card-tools">
            <a class="btn btn-warning btn-sm" href="{{ route('timesheets.labour', $timesheet->project->id) }}" title="Back">Back</a>
                <a class="btn btn-primary btn-sm print_timesheet" href="javascript:;" title="Print" id="print_timesheet" onclick="printDiv('printable_div')">Print</a>
            </div>
        </div>
        <div class="card-body printable_div" id="printable_div">
            <table class="table-responsive table table-bordered">
                <thead>
                    <tr class="table-warning">
                        <td colspan="9">
                            <strong>Project: </strong> {{ $timesheet->project->display_project_title }} <strong> Area: </strong> {{ $timesheet->mainActivity->area }} <strong> Level: </strong> {{ $timesheet->mainActivity->level }}
                        </td>
                    </tr>
                    <tr class="table-success">
                        <th>Sub Code</th>
                        <th>Date</th>
                        <th>Activity Code</th>
                        <th>Activity</th>
                        <th width="7%">Men</th>
                        <th>Allocated/hr</th>
                        <th>Spent/hr</th>
                        <th>Total Spent/hr</th>
                        <th>Remaining/hr</th>
                    </tr>
                </thead>
                <tr>
                    <td>{{ $timesheet->subActivity->sub_code }}</td>
                    <td>{{ $timesheet->timesheet_date }}</td>
                    <td>{{ $timesheet->activityOfTimesheet->item_code }}</td>
                    <td>{{ $timesheet->activity }}</td>
                    <td>{{ $timesheet->peoples }}</td>
                    <td>{{ $timesheet->allocated_hour }}</td>
                    <td>{{ $timesheet->spent_hour }}</td>
                    <td>{{ $timesheet->total_spent_hour }}</td>
                    <td>
                        @php
                            $tsh = strtok($timesheet->total_spent_hour, ':');
                            $ah = strtok($timesheet->allocated_hour, ':');
                        @endphp
                        @if($tsh > $ah){
                            <font style="color: red;">{{ $timesheet->remaining_hour }}</font>
                        @else
                            {{ $timesheet->remaining_hour }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td colspan="9" class="expandable">
                        <table class="table-responsive table table-bordered" style="width:90%;margin: auto">
                            <thead class="table-info">
                                <tr>
                                    <th>Lab Code</th>
                                    <th>Operative</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Hours</th>
                                    <th>Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($timesheet->timesheetMaterials->isNotEmpty())
                                    @foreach($timesheet->timesheetMaterials as $timesheetMaterial)
                                        <tr>
                                            <td>{{ $timesheetMaterial->lab_code }}</td>
                                            <td>{{ $timesheetMaterial->operative }}</td>
                                            <td>{{ $timesheetMaterial->start_time }}</td>
                                            <td>{{ $timesheetMaterial->end_time }}</td>
                                            <td>{{ $timesheetMaterial->hours }}</td>
                                            <td>{{ $timesheetMaterial->rate }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                @isset($timesheet->notes)
                                    <tr>
                                        <td colspan="6" class="text-center">Note:{{ $timesheet->notes }}</td>
                                    </tr>
                                @endisset
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
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