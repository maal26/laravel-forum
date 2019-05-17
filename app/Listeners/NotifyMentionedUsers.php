<?php

namespace App\Listeners;

use App\Events\ThreadReceveidNewReply;
use App\Notifications\YouWereMentioned;
use App\User;
use Illuminate\Support\Facades\Notification;

class NotifyMentionedUsers
{
    public function handle(ThreadReceveidNewReply $event)
    {
        $mentionedUsers = User::whereIn('name', $event->reply->mentionedUsers())->get();

        Notification::send($mentionedUsers, new YouWereMentioned($event->reply));
    }
}
