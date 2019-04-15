<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Tests\TestCase;

class ParticipateInThreadsTest extends TestCase
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

    /** @test */
    public function guests_users_cannot_delete_replies()
    {
        $reply = factory(Reply::class)->create();

        $this->delete("/replies/{$reply->id}")
            ->assertRedirect('login');
    }

    /** @test */
    public function unauthorized_users_cannot_delete_replies()
    {
        $reply = factory(Reply::class)->create();

        $this->signIn()
            ->delete("/replies/{$reply->id}")
            ->assertForbidden();
    }

    /** @test */
    public function authorized_users_cannot_delete_replies()
    {
        $this->signIn();

        $reply = factory(Reply::class)->create(['user_id' => auth()->id()]);

        $this->delete("/replies/{$reply->id}")
            ->assertStatus(Response::HTTP_FOUND);

        $this->assertDatabaseMissing('replies', $reply->toArray());
    }
}
