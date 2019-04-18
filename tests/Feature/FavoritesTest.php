<?php

namespace Tests\Feature;

use App\Reply;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class FavoritesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guests_cannot_favorite_anything()
    {
        $this->post('replies/1/favorites')
            ->assertRedirect('login');
    }

    /** @test */
    public function an_authenticated_user_can_favorite_any_reply()
    {
        $reply = factory(Reply::class)->create();

        $this->signIn()
            ->post("replies/{$reply->id}/favorites");

        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    public function an_authenticated_user_can_unfavorite_a_reply()
    {
        $reply = factory(Reply::class)->create();

        $this->signIn();

        $reply->favorite();

        $this->delete("replies/{$reply->id}/favorites");

        $this->assertCount(0, $reply->favorites);
    }

    /** @test */
    public function an_authenticated_user_can_may_only_favorite_a_reply_once()
    {
        $this->withoutExceptionHandling();

        $reply = factory(Reply::class)->create();

        $this->signIn();

        try {
            $this->post("replies/{$reply->id}/favorites");
            $this->post("replies/{$reply->id}/favorites");
        } catch (\Exception $e) {
            $this->fail('Did not expect to insert the same record set twice.');
        }

        $this->assertCount(1, $reply->favorites);
    }
}
