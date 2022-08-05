<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AclFiles extends Model
{
    use HasFactory;
    protected $table = 'acl_files';
    protected $fillable = ['acl_rules','file'];
}
