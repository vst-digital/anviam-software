<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatGroupMember extends Model
{
    use SoftDeletes;
    public function chatgroup() {
        return $this->belongsToMany(ChatGroup::class, 'chat_group_members',
            'id', 'group_id')
            ->withTimestamps();
    }
}
