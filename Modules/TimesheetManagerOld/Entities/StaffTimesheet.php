<?php

namespace Modules\TimesheetManager\Entities;

use Illuminate\Database\Eloquent\Model;

class StaffTimesheet extends Model {

    protected $fillable = [
        'project_id',
        'main_activity_id',
        'sub_activity_id',
        'activity_id',
        'supervisor_id',
        'approver_id',
        'activity',
        'role',
        'peoples',
        'hours',
        'notes',
        'total_hours',
        'selling_cost',
        'total_cost',
        'start_time',
        'end_time',
        'approval_date',
        'timesheet_date'
    ];

    /**
     * Get the main activity for the timesheet.
     */
    public function mainActivity()
    {
        return $this->belongsTo('Modules\EstimateManager\Entities\MainActivity');
    }

    /**
     * Get the sub activity for the timesheet.
     */
    public function subActivity()
    {
        return $this->belongsTo('Modules\EstimateManager\Entities\SubActivity');
    }

    /**
     * Get the activity for the timesheet.
     */
    public function activityOfTimesheet()
    {
        return $this->belongsTo('Modules\EstimateManager\Entities\Activity', 'activity_id');
    }

    /**
     * Get the project for the timesheet.
     */
    public function project()
    {
        return $this->belongsTo('Modules\ProjectManager\Entities\Project');
    }

    /**
     * Get the supervisor for the timesheet.
     */
    public function supervisor()
    {
        return $this->belongsTo('App\User', 'supervisor_id');
    }

    /**
     * Get the approver for the timesheet.
     */
    public function approver()
    {
        return $this->belongsTo('App\User', 'approver_id');
    }

    /**
     * Get the files of the timesheet.
     */
    public function timesheetFiles()
    {
        return $this->hasMany('Modules\TimesheetManager\Entities\StaffTimesheetFile');
    }

    /**
     * Set the timesheets date.
     *
     * @param  string  $value
     * @return void
     */
    public function setTimesheetDateAttribute($value)
    {
        $this->attributes['timesheet_date'] = \Carbon\Carbon::parse($value)->format('Y-m-d');
    }



}
