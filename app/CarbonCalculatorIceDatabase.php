<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarbonCalculatorIceDatabase extends Model
{
   protected $table = 'carbon_calculator_ice_database'; 
   protected $fillable = [
        'id',
        'materials',
        'Transport',
        'wastage',
		'quantity',
		'project_id',
		'user_id',
		'created_at'
    ];

}
