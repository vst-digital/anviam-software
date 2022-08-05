<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareFile extends Model
{
    use HasFactory;
    protected $table = 'shared_file';
    protected $fillable = ['folder_id','file_id','shared_by','shared_to'];

    // public function sharefile()
    // {
    //     return $this->belongsTo(acl_files::class,'id','id');
    // }
}
