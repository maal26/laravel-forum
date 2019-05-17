<?php

namespace App\Policies;

use App\Reply;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReplyPolicy
{
    use HandlesAuthorization;

    public function create(User $user, Reply $reply)
    {
        $lastReply = $user->fresh()->lastReply;

        if (is_null($lastReply)) {
            return true;
        }

        return ! $lastReply->wasJustPublished();
    }

    public function update(User $user, Reply $reply)
    {
        return $user->is($reply->owner);
    }
}
