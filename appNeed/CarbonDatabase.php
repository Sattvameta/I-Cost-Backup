<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarbonDatabase extends Model
{
   protected $table = 'carbon_database'; 
   protected $fillable = [
        'id',
        'materials',
        'embodied_carbon',
        'notes',
		'unit'
    ];
}
