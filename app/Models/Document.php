<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
	use HasFactory;
    use SoftDeletes;
    public function projectName()
    {
        return $this->belongsTo(Project::class,'project_id','id');
    }
    public function documentThreads() {
        return $this->hasMany(DocumentThreads::class,'document_id','id');
    }
    public function user(){
        return $this->hasOne('App\Models\User','id','created_by');
    }
    public function folderData(){
        return $this->hasOne('App\Models\DocumentsFolders','id','folder');
    }
}

?>
