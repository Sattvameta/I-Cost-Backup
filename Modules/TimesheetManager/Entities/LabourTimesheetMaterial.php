<?php

namespace Modules\TimesheetManager\Entities;

use Illuminate\Database\Eloquent\Model;

class LabourTimesheetMaterial extends Model {

    protected $fillable = [
        'labour_timesheet_id',
        'lab_code',
        'operative',
        'start_time',
        'end_time',
        'hours',
        'rate'
    ];

    /**
     * Get the activity for the timesheet.
     */
    public function timesheet()
    {
        return $this->belongsTo('Modules\TimesheetManager\Entities\LabourTimesheet');
    }

}
