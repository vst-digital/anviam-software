<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileManagerFolder extends Model
{
    use HasFactory;
    protected $table = 'acl_rules';
    protected $fillable = ['user_id','disk','path','access'];
}
