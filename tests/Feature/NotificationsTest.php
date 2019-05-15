<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class NotificationsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_notification_is_prepared_when_a_subscribred_threads_receives_a_new_reply_that_is_not_by_the_current_user()
    {
        $this->signIn();

        $thread = factory(Thread::class)
            ->create()
            ->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        $thread->addReply([
            'user_id' => auth()->id(),
            'body'    => 'Some reply here'
        ]);

        $this->assertCount(0, auth()->user()->fresh()->notifications);

        $thread->addReply([
            'user_id' => factory(User::class)->create()->id,
            'body'    => 'Some reply here'
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    /** @test */
    public function a_user_can_fetch_their_unread_notifications()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $thread = factory(Thread::class)->create()->subscribe();

        $thread->addReply([
            'user_id' => factory(User::class)->create()->id,
            'body'    => 'Some reply here'
        ]);

        $user = auth()->user();

        $this->getJson("profiles/{$user->name}/notifications")
            ->assertJsonCount(1);
    }

    /** @test */
    public function a_user_can_mark_a_notification_as_read()
    {
        $this->signIn();

        $thread = factory(Thread::class)->create()->subscribe();

        $thread->addReply([
            'user_id' => factory(User::class)->create()->id,
            'body'    => 'Some reply here'
        ]);

        $user = auth()->user();

        $this->assertCount(1, $user->unreadNotifications);

        $notificationId = $user->unreadNotifications->first()->id;

        $this->delete("profiles/{$user->name}/notifications/{$notificationId}");

        $this->assertCount(0, $user->fresh()->unreadNotifications);
    }
}
