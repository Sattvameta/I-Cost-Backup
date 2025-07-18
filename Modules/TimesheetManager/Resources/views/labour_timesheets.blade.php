@extends('user::layouts.master')

@section('content')
<style type="text/css">
    .select2.select2-container{
        width: 100% !important;
    }
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      
      <div class="col-sm-12">
        <ol class="breadcrumb float-sm-right">

          <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
          <li class="breadcrumb-item active">Timesheets</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <div class="card">
        
        <div class="card-header">
                <h3 class="card-title">Timesheets</h3><hr>
                <div class="card-tools">
                    @if(auth()->user()->can('access', 'timesheets add') && isset($project->id))
                          <a class="btn btn-success btn-sm" href="{{ route('timesheets.labour.create', $project->id) }}">Create Timesheet</a>
                    @endif
                </div>
            </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        {{ Form::label('project_filter', 'Project') }}
                        {{ Form::select('project', $allProjects, @$project->id, [
                            'class' => "form-control multiselect-dropdown project_filter",
                            'id' => "project_filter",
                            'data-live-search'=>'true',
                            'onchange'=>'changeProject(this.value)'
                        ]) }}
                    </div>
                </div>
                @isset($project)
                <div class="col-md-3">
                    <div class="form-group">
                        {{ Form::label('area_filter', 'Area') }}
                        {{ Form::select('area', $areas, null, [
                            'class' => "form-control multiselect-dropdown area_filter",
                            'id' => "area_filter",
                            'data-live-search'=>'true',
                            'onchange'=>'changeArea(this.value)'
                        ]) }}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {{ Form::label('level_filter', 'Level') }}
                        {{ Form::select('level', $levels, null, [
                            'class' => "form-control multiselect-dropdown level_filter",
                            'id' => "level_filter",
                            'data-live-search'=>'true'
                        ]) }}
                    </div>
                </div>
                @endisset
            </div>
            @isset($project) 
            @if($project->id!=$user->default_project)
                <div class="col-md-2">
                    <div class="form-group">
                        <form action="{{route('projects.make.default')}}" method="POST"> @method('PATCH') @csrf <input type="hidden" value="{{ $project->id }}" name="project_id"> <button type="submit" class="btn btn-success">Set as Default</button></form>
                      
                    </div>
                    
                </div>
            @endif                
            @endisset
        </div>
    </div>
    @isset($project)
        <!-- Default box -->
        <div class="card">
         
            <div class="card-body">
                @include('layouts.flash.alert')
				 <div class="col-md-3">
                     <div class="form-group">
						<label>Search</label><br>
						   <input id="myInput" type="text">
                            </a>
						  </div>
                      </div>
                <table class="table-responsive table table-bordered timesheet-table" id="timesheet-table">
                    <thead>
                        <tr>
                            <td colspan="11">
                                <input type="button" class="btn btn-primary btn-sm" value="Expand all" title="Expand all" id="expand_all">
                                <input type="button" class="btn btn-success btn-sm" value="Collaps all" title="Collaps all" id="collaps_all">
                            </td>
                        </tr>
                        <tr class="table-success">
                            <th width="5%">View</th>
                            <th>Sub Code</th>
                            <th>Date</th>
                            <th>Activity Code</th>
                            <th>Activity</th>
                            <th width="7%">Site Operative</th>
                            <th>Allocated/hr</th>
                            <th>Spent/hr</th>
                            <th>Total Spent/hr</th>
                            <th>Remaining/hr</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="timesheet-wrapper">
                        
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                
            </div>
        </div>
    @endisset
</section>
@stop
@push('scripts')
<script type="text/javascript">
    $(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
    
    $(document).ready(function(){
        $(document).find('#expand_all').on('click', function() {
            $('.expandable .expandable-input').val("-");
            $('.expandable').nextAll('tr').each(function() {
                if (!($(this).is('.expandable')))
                $(this).show();
            });
        });
        $(document).find('#collaps_all').on('click', function() {
            $('.expandable .expandable-input').val("+");
            $('.expandable').nextAll('tr').each(function() {
                if (!($(this).is('.expandable')))
                    $(this).hide();
            });
        });

        $(document).on('click', '.expandable-input', function() { 
            if($(this).closest('td .expandable-input').val() == "+"){
                $(this).closest('td .expandable-input').val("-");
            }else if($(this).closest('td .expandable-input').val() == "-"){
                $(this).closest('td .expandable-input').val("+");
            }
            var trElem = $(this).closest("tr");
            trElem.nextAll('tr').each(function() {
                if ($(this).is('.expandable')) {
                    return false;
                }
                $(this).toggle();
            });
        });


        var project = $(document).find('.project_filter').val();
        var area    = $(document).find('.area_filter').val();
        var level   = $(document).find('.level_filter').val();

        $(document).on('change', '.area_filter, .level_filter', function(){
            project = $(document).find('.project_filter').val();
            area    = $(document).find('.area_filter').val();
            level   = $(document).find('.level_filter').val();

            getTimesheets(project, area, level);
        });

        getTimesheets(project, area, level);
    });

    function getTimesheets(project, area, level){
        var route = "{{ route('timesheets.ajax.labour') }}";
        route +="?project="+project+"&area="+area+"&level="+level;
        $.get(route, function(data){
            $(document).find('.timesheet-wrapper').html(data.html).promise().done(function(){
                $(document).find('.expandable').next('tr').each(function() {
                    if (!($(this).is('.expandable'))){
                        $(this).hide();
                    }
                });
            });
        });
    }
    

    function changeProject(projectId) {
        window.location = "{{ route('timesheets.labour') }}/" + projectId+"?change=1";
    }
    
    function changeArea(projectId) {
        area    = $(document).find('.area_filter').val();
        $("#level_filter option[value='" + area +"']").prop("selected",true).trigger("change");

    }

</script>
<script>
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#timesheet-table tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>
@endpush