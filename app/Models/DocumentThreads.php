<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentThreads extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table="documents_threads";

    public function user(){
        return $this->hasOne('App\Models\User','id','uploaded_by');
    }
    public function threadProjectName()
    {
        return $this->belongsTo(Project::class,'project_id','id');
    }
    public function threadFolderData(){
        return $this->hasOne('App\Models\DocumentsFolders','id','folder');
    }

}
