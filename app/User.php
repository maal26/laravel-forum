<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'avatar_path'
    ];

    protected $hidden = [
        'password', 'remember_token', 'email',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function threads()
    {
        return $this->hasMany(Thread::class)->latest();
    }

    public function activity()
    {
        return $this->hasMany(Activity::class);
    }

    public function read(Thread $thread)
    {
        cache()->forever(
            auth()->user()->visitedThreadCacheKey($thread),
            now()
        );
    }

    public function visitedThreadCacheKey(Thread $thread)
    {
        return sprintf('user.%s.visits.%s', $this->id, $thread->id);
    }

    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }
}
