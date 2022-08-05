<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserMessage extends Model
{
    use SoftDeletes;
    public function message() {
        return $this->belongsTo(Message::class);
    }

    public function sender_users() {
        return $this->hasOne(User::class, 'id', 'sender_id');
    }

    public function reciever_users() {
        return $this->hasOne(User::class, 'id', 'receiver_id');
    }
}
