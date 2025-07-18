<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {

    protected $fillable = ['name', 'status', 'is_menu'];

   /**
     * Get the users for the role.
     */
    public function users() {
        return $this->belongsToMany('App\User');
    }

    /**
     * Get the permission for the role.
     */
    public function permissions() {
        return $this->belongsToMany('App\Permission');
    }

    /**
     * Get the companies for the role.
     */
    public function companies() {
        return $this->belongsToMany('App\User', 'company_id', 'id');
    }

}
