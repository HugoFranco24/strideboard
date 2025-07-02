<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Task;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $guarded = []; 

    protected $table = "projects";
    
    //Relationships
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'projects_users')                    
                    ->withPivot([
                        'user_type', 
                        'active',
                    ]);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}