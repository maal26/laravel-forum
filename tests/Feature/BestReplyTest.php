<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class BestReplyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_thread_creator_may_mark_any_reply_as_best_reply()
    {
        $this->signIn();

        $thread  = factory(Thread::class)->create(['user_id' => auth()->id()]);
        $replies = factory(Reply::class, 2)->create(['thread_id' => $thread->id]);

        $this->assertFalse($replies[1]->isBest());

        $this->postJson(route('best-replies.store', $replies[1]->id));

        $this->assertTrue($replies[1]->fresh()->isBest());
    }

    /** @test */
    public function only_the_thread_creator_may_mark_a_reply_as_best()
    {
        $this->signIn();

        $thread  = factory(Thread::class)->create(['user_id' => auth()->id()]);
        $replies = factory(Reply::class, 2)->create(['thread_id' => $thread->id]);

        $this->signIn(factory(User::class)->create());

        $this->postJson(route('best-replies.store', $replies[1]->id))
            ->assertForbidden();

        $this->assertFalse($replies[1]->fresh()->isBest());
    }

    /** @test */
    public function if_a_best_reply_is_deleted_the_thread_is_properly_updated_to_reflect_that()
    {
        $this->signIn();

        $reply = factory(Reply::class)->create(['user_id' => auth()->id()]);

        $reply->thread->markBestReply($reply);

        $this->deleteJson("/replies/{$reply->id}");

        $this->assertNull($reply->thread->fresh()->best_reply_id);
    }
}
