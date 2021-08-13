<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketReplies extends Model
{
    use HasFactory;
    protected $fillable = ['ticket_id', 'content'];

    //Create a One-To-Many Relationship 
    public function ticket()
    {
        return $this->belongsTo('App\Models\Ticket');
    }
}
