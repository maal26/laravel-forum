<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UpdateThreadTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function unauthorized_users_cannot_update_threads()
    {
        $this->signIn();

        $john   = factory(User::class)->create();
        $thread = factory(Thread::class)->create(['user_id' => $john->id]);

        $this->patch($thread->path(), [
            'title' => 'New Title',
            'body'  => 'New Body'
        ])
            ->assertForbidden();
    }

    /** @test */
    public function a_thread_can_be_updated_by_its_creator()
    {
        $this->signIn();

        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);

        $this->patch($thread->path(), [
            'title' => 'New Title',
            'body'  => 'New Body'
        ]);

        $thread->refresh();

        $this->assertEquals('New Title', $thread->title);
        $this->assertEquals('New Body', $thread->body);

        $this->assertDatabaseHas('threads', [
            'title' => $thread->title,
            'body'  => $thread->body,
        ]);
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->signIn();

        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);

        $this->patch($thread->path(), [
            'title' => 'New Title',
        ])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_requires_a_title()
    {
        $this->signIn();

        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);

        $this->patch($thread->path(), [
            'body' => 'New Body',
        ])
            ->assertSessionHasErrors('title');
    }
}
