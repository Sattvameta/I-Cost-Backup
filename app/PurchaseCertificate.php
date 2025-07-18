<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseCertificate extends Model
{
	 protected $table = 'purchase_certificate'; //type the table name
  
     protected $fillable = [
        'purchase_no',
        'certificate'
    ];

   
}
