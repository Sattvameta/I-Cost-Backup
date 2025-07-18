<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseDeliveryNote extends Model
{
	 protected $table = 'purchase_deliverynote'; //type the table name
      protected $fillable = [
        'purchase_no',
        'delivery_note',
        'note'
    ];

  
}
