<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class VstModule extends Model
{
    use HasFactory;
     use SoftDeletes;
      protected $table = 'vst_module';
    protected $fillable = ['name'];
}
