<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyFeature extends Model {

    protected $fillable = ['feature_id','company_id'];
    

     public function companies() {
        return $this->belongsTo('App\User','company_id', 'id');
    }

    public function features() {
        return $this->belongsTo('App\Feature','feature_id', 'id');
    }

}
