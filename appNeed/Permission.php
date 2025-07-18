<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model {

  
    protected $fillable = [];

    /**
     * Get the roles for the permission.
     */
    public function roles() {
        return $this->belongsToMany('App\Role');
    }
}
