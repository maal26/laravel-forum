<?php

namespace Tests\Feature;

use App\Thread;
use App\Trending;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class TrendingThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected $trending;

    public function setUp(): void
    {
        parent::setUp();

        $this->trending = new Trending;

        $this->trending->reset();
    }

    /** @test */
    public function it_increments_a_threads_score_each_time_it_its_read()
    {
        $this->assertEmpty($this->trending->get());

        $thread = factory(Thread::class)->create();

        $this->get($thread->path());

        $trending = $this->trending->get();

        $this->assertCount(1, $trending);

        $this->assertEquals($thread->title, $trending[0]->title);
    }
}
