<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inbox extends Model
{
    protected $guarded = []; 

    protected $table = "inbox";
    
    //Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}