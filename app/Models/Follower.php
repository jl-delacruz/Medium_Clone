<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    public const UPDATED_AT = null; // Disable updated_at timestamp
    protected $fillable = ['user_id', 'follower_id'];

    //to be able to access the user who is being followed
    //ex: $follower->user->name
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //to be able to access the follower
    public function follower()
    {
        return $this->belongsTo(User::class);
    }
}
