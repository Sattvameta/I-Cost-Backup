<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarbonCalculatorDatabase extends Model
{
     protected $table = 'carbon_database_carbon_calculator';
      protected $fillable = [
        'user_id',
        'carbon_database_id',
        'carbon_a_one_a_five_id',
        'user_database_id'
    ];
}
