<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarbonCalculatorUserDatabase extends Model
{
     protected $table = 'carbon_calculator_user_database'; 
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
