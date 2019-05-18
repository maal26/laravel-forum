<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AddAvatarTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function only_members_can_add_avatars()
    {
        $this->postJson('users/1/avatar')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function a_valid_avatar_must_be_provided()
    {
        $this->signIn();

        $this->postJson('users/' . auth()->id() . '/avatar', [
            'avatar' => 'not-an-image'
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function a_user_can_add_an_avatar_to_their_profile()
    {
        $this->signIn();

        Storage::fake('public');

        $file = UploadedFile::fake()->image('avatar.jpg');

        $this->postJson('users/' . auth()->id() . '/avatar', [
            'avatar' => $file
        ]);

        $this->assertEquals(
            url("/storage/avatars/{$file->hashName()}"),
            auth()->user()->avatar_path
        );

        Storage::disk('public')->assertExists('avatars/' . $file->hashName());
    }
}
