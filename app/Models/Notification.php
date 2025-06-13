<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Task;

class Notification extends Model
{
    protected $guarded = []; 

    protected $table = "notifications";
    
    //Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}