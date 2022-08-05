<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentsFolders extends Model
{
	use HasFactory;
    use SoftDeletes;
    public function user(){
        return $this->hasOne('App\Models\User','id','user_id');
    }
}

?>
