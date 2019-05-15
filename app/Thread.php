<?php

namespace App;

use App\Filters\Filters;
use App\Notifications\ThreadWasUpdated;
use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;

    protected $fillable = ['title', 'body', 'user_id', 'channel_id'];

    protected static $recordableEvents = ['created', 'deleting'];

    protected $with = ['creator', 'channel'];

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

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
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

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function scopeFilter(Builder $query, Filters $filters)
    {
        return $filters->apply($query);
    }

    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        $this->subscriptions
            ->filter(function ($subscription) use ($reply) {
                return $subscription->user_id !== $reply->user_id;
            })
            ->each->notify($reply);

        return $reply;
    }

    public function subscribe(? int $userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?? auth()->id()
        ]);

        return $this;
    }

    public function unsubscribe(? int $userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?? auth()->id())
            ->delete();
    }
}
