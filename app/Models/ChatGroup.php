<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatGroup extends Model
{
    use SoftDeletes;

    public function users() {
        return $this->belongsToMany(User::class, 'chat_group',
            'user_id')
            ->withTimestamps();
    }
}
