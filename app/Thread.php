<?php

namespace App;

use App\Filters\Filters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    protected $fillable = ['title', 'body', 'user_id', 'channel_id'];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('replyCount', function ($builder) {
            $builder->withCount('replies');
        });
    }

    public function path(?string $segment = null)
    {
        return is_null($segment)
            ? "/threads/{$this->channel->slug}/{$this->id}"
            : "/threads/{$this->channel->slug}/{$this->id}/{$segment}";
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function addReply($reply)
    {
        $this->replies()->create($reply);
    }

    public function scopeFilter(Builder $query, Filters $filters)
    {
        return $filters->apply($query);
    }
}
