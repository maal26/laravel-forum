<?php

namespace Tests\Feature;

use App\Channel;
use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Tests\TestCase;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;

    private $thread;

    protected function setUp(): void
    {
        parent::setUp();

        $this->thread = factory(Thread::class)->create();
    }

    /** @test */
    public function a_user_can_view_all_threads()
    {
        $this->get('/threads')
            ->assertStatus(Response::HTTP_OK)
            ->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_read_a_single_thread()
    {
        $this->get($this->thread->path())
            ->assertStatus(Response::HTTP_OK)
            ->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        $reply = factory(Reply::class)->create(['thread_id' => $this->thread->id]);

        $this->get($this->thread->path())
            ->assertStatus(Response::HTTP_OK)
            ->assertSee($reply->body);
    }

    /** @test */
    public function a_user_can_filter_threads_according_to_a_channel()
    {
        $channel            = factory(Channel::class)->create();
        $threadInChannel    = factory(Thread::class)->create(['channel_id' => $channel->id]);
        $threadNotInChannel = factory(Thread::class)->create();

        $this->get("/threads/{$channel->slug}")
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    /** @test */
    public function a_user_can_filter_threads_by_a_username()
    {
        $john = factory(User::class)->create(['name' => 'JohnDoe']);

        $threadByJohn    = factory(Thread::class)->create(['user_id' => $john->id]);
        $threadNotByJohn = factory(Thread::class)->create();

        $this->signIn($john)
            ->get('/threads?by=JohnDoe')
            ->assertSee($threadByJohn->title)
            ->assertDontSee($threadNotByJohn->title);
    }
}
