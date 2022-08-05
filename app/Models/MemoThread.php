<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class MemoThread extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table="memo_threads";

    public function memoName()
    {
        return $this->belongsTo(Memo::class,'project_id','id');
    }
    public function memoUsers()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}
