<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectUser extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    
    public $timestamps = false;
    
    protected $table = "projects_users";
}