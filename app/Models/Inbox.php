<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inbox extends Model
{
    protected $guarded = []; 

    protected $table = "inbox";
    
    //Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}