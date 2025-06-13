<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Task;

class Project extends Model
{
    protected $guarded = []; 

    protected $table = "projects";
    
    //Relationships
    public function users()
    {
        return $this->belongsToMany(User::class, 'projects_users')                    
                    ->withPivot([
                        'user_type', 
                        'active',
                    ]);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}