<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Project extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table="projects";
    protected $fillable = [
        'created_by','company_id','project_type_id','name','description','project_status','start_date','end_date'
    ];


    public function projectName()
    {
        return $this->belongsTo(ProjectType::class,'project_type_id','id');
    }
    public function projectUsers()
    {
        return $this->belongsToMany(User::class,'project_users','project_id','user_id');
    }
    public function projectAttachment()
    {
        //return $this->hasOne(ProjectAttachments::class,'project_id','id');
        return $this->hasMany('App\Models\ProjectAttachments', 'project_id');
        
    }

    public function userFillName(){
         return $this->hasOne(ProjectAttachments::class);
     }

}
