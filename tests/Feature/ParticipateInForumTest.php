<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guests_cannot_add_replies()
    {
        $thread = factory(Thread::class)->create();

        $this->post($thread->path('replies'), [])
            ->assertRedirect('login');
    }

    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        $user   = factory(User::class)->create();
        $thread = factory(Thread::class)->create();
        $reply  = factory(Reply::class)->raw();

        $this->signIn($user)
            ->followingRedirects()
            ->post($thread->path('replies'), $reply)
            ->assertSuccessful()
            ->assertSee($reply['body']);
    }

    /** @test */
    public function a_reply_requires_a_body()
    {
        $user   = factory(User::class)->create();
        $thread = factory(Thread::class)->create();
        $reply  = factory(Reply::class)->raw(['body' => null]);

        $this->signIn($user)
            ->post($thread->path('replies'), $reply)
            ->assertSessionHasErrors('body');
    }
}
