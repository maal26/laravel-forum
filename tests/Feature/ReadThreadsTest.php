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

    /** @test */
    public function a_user_can_filter_threads_by_popularity()
    {
        $threadWithNoReplies = $this->thread;

        $threadWithTwoReplies = factory(Thread::class)->create();
        factory(Reply::class, 2)->create(['thread_id' => $threadWithTwoReplies->id]);

        $threadWithThreeReplies = factory(Thread::class)->create();
        factory(Reply::class, 3)->create(['thread_id' => $threadWithThreeReplies->id]);

        $this->get('/threads?popular=1')
            ->assertSeeInOrder([
                $threadWithThreeReplies->title,
                $threadWithTwoReplies->title,
                $threadWithNoReplies->title
            ]);
    }

    /** @test */
    public function a_user_can_filter_threads_by_those_that_are_unanswered()
    {
        $thread = factory(Thread::class)->create();
        factory(Reply::class)->create(['thread_id' => $this->thread->id]);

        $response = $this->get('/threads?unanswered=1');

        $this->assertCount(1, $response->original->getData()['threads']);
    }

    /** @test */
    public function a_user_can_request_all_replies_for_a_given_thread()
    {
        $thread = factory(Thread::class)->create();
        $reply  = factory(Reply::class, 2)->create(['thread_id' => $thread->id]);

        $response = $this->getJson($thread->path('replies'))->json();

        $this->assertCount(2, $response['data']);
        $this->assertEquals(2, $response['total']);
    }
}
