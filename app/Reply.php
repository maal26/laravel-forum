<?php

namespace App;

use App\Traits\Favoritable;
use App\Traits\RecordsActivity;
use App\Traits\WithPolicy;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favoritable, RecordsActivity, WithPolicy;

    protected $fillable = ['body', 'user_id'];

    protected $with = ['owner', 'favorites'];

    protected $appends = ['favoritesCount', 'isFavorited', 'can'];

    protected $touches = ['thread'];

    protected static $recordableEvents = ['created', 'deleting'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function path()
    {
        return $this->thread->path() . "#reply-{$this->id}";
    }

    public function wasJustPublished()
    {
        return $this->created_at->gt(now()->subMinute());
    }
}
