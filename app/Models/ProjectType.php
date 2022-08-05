<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table="project_types";
    protected $fillable = [
        'company_id','created_by','name','description','status'
    ];
}
