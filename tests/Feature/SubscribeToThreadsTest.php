<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SubscribeToThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_subscribe_to_threads()
    {
        $thread = factory(Thread::class)->create();

        $this->signIn()
            ->post($thread->path('subscriptions'));

        $thread->addReply([
            'user_id' => auth()->id(),
            'body'    => 'Some reply here'
        ]);
    }
}
