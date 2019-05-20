<?php

namespace Tests\Feature;

use App\Mail\PleaseConfirmYourEmail;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_confirmation_email_is_sent_upon_registration()
    {
        Mail::fake();

        $this->post(route('register'), [
            'name'                  => 'John',
            'email'                 => 'john.doe@mail.com',
            'password'              => '12345678',
            'password_confirmation' => '12345678'
        ]);

        Mail::assertQueued(PleaseConfirmYourEmail::class);
    }

    /** @test */
    public function user_can_fully_confirm_their_email_address()
    {
        Mail::fake();

        $this->post(route('register'), [
            'name'                  => 'John',
            'email'                 => 'john.doe@mail.com',
            'password'              => '12345678',
            'password_confirmation' => '12345678'
        ]);

        $user = User::whereName('John')->first();

        $this->assertNull($user->email_verified_at);
        $this->assertNotNull($user->confirmation_token);

        $this->get("/register/confirm?token={$user->confirmation_token}")
            ->assertRedirect('/threads');

        $this->assertInstanceOf(Carbon::class, $user->fresh()->email_verified_at);
    }

    /** @test */
    public function confirm_an_invalid_token()
    {
        $this->withoutExceptionHandling();

        $token = Str::random(36);

        $this->get("/register/confirm?token={$token}")
            ->assertRedirect('/threads')
            ->assertSessionHas('flash', 'Unknown token.');
    }

}
