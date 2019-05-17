<?php

namespace App\Listeners;

use App\Events\ThreadReceveidNewReply;

class NotifySubscribers
{
    /**
     * Handle the event.
     *
     * @param  ThreadReceveidNewReply  $event
     * @return void
     */
    public function handle(ThreadReceveidNewReply $event)
    {
        $thread = $event->reply->thread;

        $thread->subscriptions
            ->where('user_id', '!=', $event->reply->user_id)
            ->each
            ->notify($event->reply);
    }
}
