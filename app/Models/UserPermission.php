<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class UserPermission extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'user_permission';
    protected $fillable = ['user_id','module_id'];
}
