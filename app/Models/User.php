<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use SoftDeletes;
    public $project_id;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'fname',
        'lname',
        'number',
        'project_id',
        'department_id'
    ];
    

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $with = ['permissions'];

    

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function company_role()
    {
        return $this->belongsTo(CompanyRole::class,'company_role_id','id');
    }
    public function getProjectIdAttribute($value) 
    {     
        return $value;
    }
    // public function getProjectIdAttribute($value)
    // {      
    //     // return $value;
    //     if(session()->has('id')) {
        
    //         $project_id = $this->projects->id;
    //         // Save it to session
    //         $project = session(['project_id' => $project_id]);
    //         return $project;
    //     }
    

    public function permissions()
    {
        return $this->hasMany(UserPermission::class,'user_id','id');
    }


}
