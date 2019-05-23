<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Tests\TestCase;

class LockThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function non_administrators_may_not_lock_threads()
    {
        $this->signIn();

        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);

        $this->post("locked-threads/{$thread->slug}")
            ->assertForbidden();

        $this->assertFalse($thread->fresh()->locked);
    }

    /** @test */
    public function administrators_can_lock_threads()
    {
        $john = factory(User::class)->state('adm')->create();

        $this->signIn($john);

        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);

        $this->assertFalse($thread->locked);

        $this->post("locked-threads/{$thread->slug}")
            ->assertStatus(Response::HTTP_OK);

        $this->assertTrue($thread->fresh()->locked);
    }

    /** @test */
    public function administrators_can_unlock_threads()
    {
        $john = factory(User::class)->state('adm')->create();

        $this->signIn($john);

        $thread = factory(Thread::class)->create([
            'user_id' => auth()->id(),
            'locked'  => true
        ]);

        $this->delete("locked-threads/{$thread->slug}")
               ->assertStatus(Response::HTTP_OK);

        $this->assertFalse($thread->fresh()->locked);
    }

    /** @test */
    public function once_locked_a_thread_may_not_receive_new_replies()
    {
        $this->signIn();

        $thread = factory(Thread::class)->create();

        $thread->lock();

        $this->post($thread->path('replies'), [
            'body'    => 'Foobar',
            'user_id' => auth()->id()
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
