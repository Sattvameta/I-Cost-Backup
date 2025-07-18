<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarbonMainActivity extends Model
{ 
      protected $table = 'user_carbon_database';
      protected $fillable = [
        'materials',
        'factors',
        'mass'
    ];

    
}
