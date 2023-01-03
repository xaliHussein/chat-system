<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $with = ['chat_messages','user'];
    public function chat_messages()
    {
        return $this->hasMany(ChatMessages::class);
    }

    public function user()
    {
        return $user= $this->belongsTo(User::class,'sender_id')
        ->orWhere('id','!=',auth()->user()->id);
    }
    // public function user2()
    // {
    //     return $this->belongsTo(User::class,'receiver_id')
    //     ->orWhere('id','!=',auth()->user()->id);
    // }
}
