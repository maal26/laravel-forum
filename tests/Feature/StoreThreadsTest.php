<?php

namespace Tests\Feature;

use App\Channel;
use App\Rules\Recaptcha;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Str;
use Tests\TestCase;

class StoreThreadsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        // $m = \Mockery::mock(Recaptcha::class);

        // $m->shouldReceive('passes')->andReturn(true);

        // app()->instance(Recaptcha::class, $m);
    }

    /** @test */
    public function guests_may_not_create_threads()
    {
        $this->get('/threads/create')
            ->assertRedirect('login');

        $this->post('/threads', [])
            ->assertRedirect('login');
    }

    /** @test */
    public function new_users_must_first_confirm_their_email_address_before_creating_threads()
    {
        $this->publishThread([], ['email_verified_at' => null])
            ->assertRedirect('/threads')
            ->assertSessionHas('flash', 'You must first confirm your email address');
    }

    /** @test */
    public function a_user_can_create_new_forum_threads()
    {
        $thread = factory(Thread::class)->make();

        $this->signIn()
            ->followingRedirects()
            ->post('/threads', $thread->toArray())
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /** @test */
    public function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => ''])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->publishThread(['title' => Str::random(256)])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_recaptcha_verification()
    {
        $this->publishThread(['g-recaptcha-response' => 'test'])
            ->assertSessionHasErrors('g-recaptcha-response');
    }

    /** @test */
    public function a_thread_requires_a_valid_channel()
    {
        factory(Channel::class, 2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    public function a_thread_title_cannot_pass_over_than_255_characters()
    {
        $this->publishThread(['body' => ''])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_requires_a_unique_slug()
    {
        $this->signIn();

        $thread = factory(Thread::class)->create(['title' => 'Foo title', 'slug' => 'foo-title']);

        $this->assertEquals('foo-title', $thread->slug);

        $this->post('/threads', $thread->toArray());

        $this->assertTrue(Thread::whereSlug('foo-title-2')->exists());

        $this->post('/threads', $thread->toArray());

        $this->assertTrue(Thread::whereSlug('foo-title-3')->exists());
    }

    /** @test */
    public function a_thread_with_a_title_that_ends_in_a_number_should_generate_the_proper_slug()
    {
        $this->signIn();

        $thread = factory(Thread::class)->create(['title' => 'Some Title 24', 'slug' => 'some-title-24']);

        $this->post('/threads', $thread->toArray());

        $this->assertTrue(Thread::whereSlug('some-title-24-2')->exists());
    }

    public function publishThread(array $threadOverrides = [], array $userOverrides = [])
    {
        $thread = factory(Thread::class)->make($threadOverrides);

        return $this->signIn(null, $userOverrides)
            ->post('/threads', $thread->toArray());
    }
}
