<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectUser extends Model
{
    protected $guarded = [];
    
    public $timestamps = false;
    
    protected $table = "projects_users";
}