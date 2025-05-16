<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Task;

class Project extends Model
{
    use SoftDeletes;

    protected $guarded = []; 

    protected $table = "projects";
    
    //Relationships
    public function users()
    {
        return $this->belongsToMany(User::class, 'projects_users')                    
                    ->withPivot('user_type')->whereNull('projects_users.deleted_at');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}