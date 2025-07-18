@extends('user::layouts.master')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Manage Timesheet</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('timesheets.staff', $timesheet->project->id) }}">Timesheets</a></li>
          <li class="breadcrumb-item active">Gallery</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Gallery</h3>
            <div class="card-tools">
                <a class="btn btn-default btn-sm" href="{{ route('timesheets.staff', $timesheet->project->id) }}" title="Back">Back</a>
            </div>
        </div>
        <div class="card-body">
            <div>
                <div class="btn-group w-100 mb-2">
                    <a class="btn btn-info active" href="javascript:void(0)" data-filter="all"> All items </a>
                    <a class="btn btn-info" href="javascript:void(0)" data-filter="site_diaries"> Site Diries </a>
                    <a class="btn btn-info" href="javascript:void(0)" data-filter="images"> Images </a>
                    <a class="btn btn-info" href="javascript:void(0)" data-filter="person_photo"> Person Images </a>
                    <a class="btn btn-info" href="javascript:void(0)" data-filter="drawings"> Drawings </a>
                </div>
                <div class="mb-2">
                    <a class="btn btn-secondary" href="javascript:void(0)" data-shuffle> Shuffle items </a>
                    <div class="float-right">
                        <select class="custom-select" style="width: auto;" data-sortOrder>
                            <option value="index"> Sort by Position </option>
                        </select>
                        <div class="btn-group">
                            <a class="btn btn-default" href="javascript:void(0)" data-sortAsc> Ascending </a>
                            <a class="btn btn-default" href="javascript:void(0)" data-sortDesc> Descending </a>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="filter-container p-0 row">
                    @if($timesheet->timesheetFiles->isNotEmpty())
                        @foreach($timesheet->timesheetFiles as $timesheetFile)
                            <div class="filtr-item col-sm-2" data-category="{{ $timesheetFile->category }}">
                                <div class="text-center">
                                    @if ((pathinfo(asset('storage/timesheet_files/'.$timesheetFile->category.'/'.$timesheetFile->file), PATHINFO_EXTENSION) == 'jpg') || (pathinfo(asset('storage/timesheet_files/'.$timesheetFile->category.'/'.$timesheetFile->file), PATHINFO_EXTENSION) == 'png') || (pathinfo(asset('storage/timesheet_files/'.$timesheetFile->category.'/'.$timesheetFile->file), PATHINFO_EXTENSION) == 'jpeg'))
                                        <a href="{{ asset('storage/timesheet_files/'.$timesheetFile->category.'/'.$timesheetFile->file) }}" data-toggle="lightbox" data-title="{{ $timesheetFile->file }}">
                                            <!--<img src="{{ asset('storage/timesheet_files/'.$timesheetFile->category.'/'.$timesheetFile->file) }}" class="" alt="{{ $timesheetFile->file }}" height="150px" width="100%" />-->
                                            <img src="<?php $articleimage= url('/');  echo substr($articleimage, 0, strrpos($articleimage, "/")).'/storage/app/public/timesheet_files/'.$timesheetFile->category.'/'.$timesheetFile->file; ?>" class="" alt="{{ $timesheetFile->file }}" height="150px" width="100%" />
                                        </a>
                                    @else
                                        <a href="javascript:;" title="{{ $timesheetFile->file }}">
                                            <img src="{{ asset('images/no-img-100x92.jpg') }}" class="" alt="{{ $timesheetFile->file }}" height="150px" width="100%" />
                                        </a>
                                    @endif
                                    <h6>{{ $timesheetFile->file }}</h6>
                                    <a href="{{ route('timesheets.download.staff.file', $timesheetFile->id) }}" class="btn btn-warning btn-sm">Download</a>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@stop
@push('scripts')
    <script type="text/javascript">
        $(function () {
            $(document).on('click', '[data-toggle="lightbox"]', function(event) {
                event.preventDefault();
                $(this).ekkoLightbox({
                    alwaysShowClose: true
                });
            });

            $('.filter-container').filterizr({gutterPixels: 3});
                $('.btn[data-filter]').on('click', function() {
                $('.btn[data-filter]').removeClass('active');
                $(this).addClass('active');
            });
        })
    </script>
@endpush