<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;
    public function user_messages() {
        return $this->hasMany(UserMessage::class);
    }

    public function users() {
        return $this->belongsToMany(User::class, 'user_messages',
            'message_id', 'sender_id')
            ->withTimestamps();
    }
    public function getMessageAttribute($value)
    {
        if($this->type == 2){
            if($value!= ''){
                return url('/uploads/chatImages/'.$value);
            }else{
                return '';
            }
        }else{
            return $value;
        }

    }
}
