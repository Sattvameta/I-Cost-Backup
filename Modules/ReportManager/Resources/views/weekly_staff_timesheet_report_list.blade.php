<div class="card-header">
    <h3 class="card-title">Weekly Staff Timesheet Report</h3>
</div>
<div class="card-body">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                {{ Form::label('date_from_filter', 'From') }}
                {{ Form::date('date', null, [
                    'class' => "form-control date_from_filter",
                    'id' => "date_from_filter",
                ]) }}
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="form-group">
                {{ Form::label('user_filter', 'User') }}
                {{ Form::select('user', $users, null, [
                    'class' => "form-control multiselect-dropdown user_filter",
                    'id' => "user_filter",
                    'data-live-search'=>'true',
                ]) }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <button type="button" class="btn btn-success btn-sm filter-staff-weekly-timesheet">Filter</button>
            <a href="javascript:;" onclick="printDiv('printable-div')" class="btn btn-info btn-sm">Print</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 timesheets-list-wrapper"></div>
    </div>
</div>