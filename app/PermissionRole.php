<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model {

    protected $table = 'permission_role';

    protected $fillable = ['role_id','permission_id','company_id'];
    
    public function roles() {
        return $this->belongsTo('App\Role','role_id', 'id');
    }
    public function companies() {
        return $this->belongsTo('App\User','company_id', 'id');
    }
    public function permissions() {
        return $this->belongsTo('App\Permission','permission_id', 'id');
    }

}
