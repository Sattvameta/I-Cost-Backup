<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarbonTotalUser extends Model
{
    protected $table = 'v_carbon_database_calculator_user_total'; 
   protected $fillable = [
        'id',
        'Total',
        'project_id',
        'user_id',
        'created_at'
    ];
}
