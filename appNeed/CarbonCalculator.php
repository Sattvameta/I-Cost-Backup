<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarbonCalculator extends Model
{
   protected $table = 'carbon_calculator'; 
   protected $fillable = [
        'id',
        'materials',
        'Transport',
        'wastage',
		'quantity',
		'project_id',
		'created_at'
    ];

}
