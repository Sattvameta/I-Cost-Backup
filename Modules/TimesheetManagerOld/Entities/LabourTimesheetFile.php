<?php

namespace Modules\TimesheetManager\Entities;

use Illuminate\Database\Eloquent\Model;

class LabourTimesheetFile extends Model {

    protected $fillable = [
        'labour_timesheet_id',
        'category',
        'file'
    ];

    /**
     * Get the activity for the timesheet.
     */
    public function timesheet()
    {
        return $this->belongsTo('Modules\TimesheetManager\Entities\LabourTimesheet');
    }

}
