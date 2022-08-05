<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyRole extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table="company_roles";
    protected $fillable = [
        'name','description','company_id'
    ];


}
