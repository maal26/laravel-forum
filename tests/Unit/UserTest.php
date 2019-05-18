<?php

namespace Tests\Unit;

use App\Reply;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_ser_can_fetch_their_most_recent_reply()
    {
        $user  = factory(User::class)->create();
        $reply = factory(Reply::class)->create(['user_id' => $user->id]);

        $this->assertEquals($reply->id, $user->lastReply->id);
    }

    /** @test */
    public function a_user_can_determine_their_avatar_path()
    {
        $user = factory(User::class)->create();

        $this->assertEquals('/images/default.jpg', $user->avatar_path);

        // $user->avatar_path = 'avatars/me.jpg';

        // $this->assertEquals('avatars/me.jpg', $user->avatar());
    }
}
