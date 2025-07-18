<?php

namespace Modules\TimesheetManager\Entities;

use Illuminate\Database\Eloquent\Model;

class LabourTimesheet extends Model {

    protected $fillable = [
        'project_id',
        'main_activity_id',
        'sub_activity_id',
        'activity_id',
        'supervisor_id',
        'activity',
        'peoples',
        'hours',
        'notes',
        'allocated_hour',
        'total_spent_hour',
        'remaining_hour',
        'spent_hour',
        'timesheet_date'
    ];

    /**
     * Get the materials of the timesheet.
     */
    public function timesheetMaterials()
    {
        return $this->hasMany('Modules\TimesheetManager\Entities\LabourTimesheetMaterial');
    }

    /**
     * Get the files of the timesheet.
     */
    public function timesheetFiles()
    {
        return $this->hasMany('Modules\TimesheetManager\Entities\LabourTimesheetFile');
    }

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
