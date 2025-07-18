@extends('user::layouts.master')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      
      <div class="col-sm-12">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('timesheets.staff', $timesheet->project->id) }}">Timesheets</a></li>
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
            <a class="btn btn-warning btn-sm" href="{{ route('timesheets.staff', $timesheet->project->id) }}" title="Back">Back</a>
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
                        <th>Activity Code</th>
                        <th>Sub Code</th>
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Hours</th>
                        <th>People</th>
                        <th>Total Hours</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $total_hour = 0;
                        $tmj = 0;
                        $detailTimesheets = $timesheet->project->staffTimesheets()
                                ->where('role', 'detail')
                                ->where('sub_activity_id', $timesheet->sub_activity_id)
                                ->groupBy('activity_id')
                                ->get();
                    @endphp
                    @if($detailTimesheets->isNotEmpty())
                        @foreach($detailTimesheets as $detailTimesheet)
                            @php
                                $detailTimesheets1 = $timesheet->project->staffTimesheets()
                                        ->where('role', 'detail')
                                        ->where('main_activity_id', $timesheet->main_activity_id)
                                        ->where('sub_activity_id', $timesheet->sub_activity_id)
                                        ->where('activity_id', $detailTimesheet->activity_id)
                                        ->get();
                            @endphp
                            @if($detailTimesheets1->isNotEmpty())
                                @foreach($detailTimesheets1 as $detailTimesheet1)
                                    @php 
                                        $tt = $detailTimesheet1->total_hours;	 
                                        $a = '';
                                        $a = $detailTimesheet1->total_hours;
                                        if (strpos($a, ':') !== false) {
                                            
                                        }else{
                                            $tt = str_pad($tt, 2, '0', STR_PAD_LEFT).':00';
                                        }   
                                        $dtm = new DateTime('2019-11-09 '.$tt.'');
                                        $time = $dtm->format('H:i');
                                        $ph = $dtm->format('H');
                                        $pm = $dtm->format('i');
                                        $tmin = ($ph*60)+$pm;	
                                        $user = \App\User::where('email', $detailTimesheet1->supervisor_email)->first();
                                    @endphp
                                    <tr>
                                        <td>{{ $detailTimesheet1->activity }}</td>
                                        <td>{{ $detailTimesheet1->subActivity->activity }} / {{ $user ? $user->meuser_name : '' }}</td>
                                        <td>{{ $detailTimesheet1->timesheet_date }}</td>
                                        <td>{{ $detailTimesheet1->start_time }}</td>
                                        <td>{{ $detailTimesheet1->end_time }}</td>
                                        <td>{{ $tt }}</td>
                                        <td>{{ $detailTimesheet1->peoples }}</td>
                                        <td>{{ $tt }}</td>
                                    </tr>
                                    @php 
                                        $tmj=$tmj+$pm;	 
                                        @$total_hour=$total_hour+$ph;
                                    @endphp
                                @endforeach
                            @endif
                            @php 
                                if($tmj > 60){
                                    $total_hour = $total_hour + floor($tmj/60);
                                    $tmj = floor($tmj%60);
                                }
                            @endphp
                        @endforeach
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="8" class="text-center">
                            <b>Total Hours : {{ $total_hour }}:{{ $tmj }}</b>
                        </td>
                    <tr>
                </tfoot>
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