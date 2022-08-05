<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectAttachments extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table="project_attachments";
    protected $fillable = [
        'project_id','title','attachment','uploaded_by'
    ];    

    public function user(){
        return $this->hasOne('App\Models\User','id','uploaded_by');
    }

}
