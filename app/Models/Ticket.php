<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = ['category', 'subject', 'decsription', 'user_id','status'];
    

    //Create a One-To-Many Relationship 
    public function User()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function ticketReplies()
    {
        return $this->hasMany('App\Models\TicketReplies');
    }
}
