<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarbonSubActivity extends Model
{
	 protected $table = 'carbon_sub_activities';
     protected $fillable = [
        'main_activity_id',
        'sub_code',
        'activity',
        'quantity',
        'rate',
        'total',
        'hr',
        'mhr',
        'total_hr',
        'total_mhr',
        'unit'
    ];

    /**
     * Get the project for the estimate.
     */
    public function mainActivity()
    {
        return $this->belongsTo('App\CarbonMainActivity');
    }

    /**
     * Get the sctivities of the sub activity.
     */
    public function activities()
    {
        return $this->hasMany('App\CarbonActivity');
    }

    /**
     * Get the activities display name attribute.
     */
    public function getActivityDisplayNameAttribute(){
        return $this->activity."(".$this->sub_code.")";
    }
}
