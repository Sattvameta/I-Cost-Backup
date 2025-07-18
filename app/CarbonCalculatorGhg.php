<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarbonCalculatorGhg extends Model
{
     protected $table = 'carbon_calculator_ghg';
      protected $fillable = [
        'id',
        'materials',
        'Transport',
        'wastage',
		'quantity',
		'project_id',
		'user_id'
    ];
}
