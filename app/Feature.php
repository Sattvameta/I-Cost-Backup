<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model {

  
    protected $fillable = [];

    /**
     * Get the companies for the feature.
     */
    public function companies() {

        return $this->belongsToMany('App\User');
        
    }
}
