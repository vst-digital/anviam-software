<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
	use HasFactory;
    use SoftDeletes;
	protected $fillable = [
		'title', 'color', 'start', 'end', 'description', 'users', 'referenceto', 'references','created_by'
	];
}

?>
