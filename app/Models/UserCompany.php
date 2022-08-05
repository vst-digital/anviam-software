<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCompany extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table="user_company";
    protected $fillable = [
        'user_id','company_name','company_number','street','city','state','zip','country'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

}
