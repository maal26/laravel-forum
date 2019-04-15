<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class DeleteThreadTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function unauthorized_users_may_not_delete_threads()
    {
        $thread = factory(Thread::class)->create();

        $this->delete($thread->path(), ['id' => $thread->id])
            ->assertRedirect('login');

        $this->signIn()
            ->delete($thread->path(), ['id' => $thread->id])
            ->assertForbidden();
    }

    /** @test */
    public function authorized_users_can_delete_threads()
    {
        $this->signIn();

        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);
        $reply  = factory(Reply::class)->create(['thread_id' => $thread->id]);
        $this->delete($thread->path(), ['id' => $thread->id]);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }

    public function threads_may_only_be_deleted_by_those_who_have_permission()
    {

    }
}
