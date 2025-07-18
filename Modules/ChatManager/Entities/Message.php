<?php

namespace Modules\ChatManager\Entities;

use Illuminate\Database\Eloquent\Model;

class Message extends Model {

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
    ];

    /**
     * Get the sender for the message.
     */
    public function sender()
    {
        return $this->belongsTo('App\User', 'sender_id');
    }

    /**
     * Get the receiver for the message.
     */
    public function receiver()
    {
        return $this->belongsTo('App\User', 'receiver_id');
    }

}
