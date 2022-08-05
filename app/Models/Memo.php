<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Memo extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table="memos";

    public function projectName()
    {
        return $this->belongsTo(Project::class,'project_id','id');
    }
    public function memoUsers()
    {
        return $this->belongsToMany(User::class,'memo_users','memo_id','user_id')->withTimestamps();
    }

    public function memoThreads() {
        return $this->hasMany(MemoThread::class,'memo_id','id');
    }
}
