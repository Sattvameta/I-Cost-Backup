<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Projectuser extends Model
{
    protected $table = 'users_project'; //type the table name
    protected $fillable = ['id','users_id','project_id','projects_status','created_at','updated_at'];
}
