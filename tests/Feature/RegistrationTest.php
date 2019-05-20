<?php

namespace Tests\Feature;

use App\Mail\PleaseConfirmYourEmail;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_confirmation_email_is_sent_upon_registration()
    {
        Mail::fake();

        $user = factory(User::class)->create();

        event(new Registered($user));

        Mail::assertSent(PleaseConfirmYourEmail::class);
    }

    /** @test */
    public function user_can_fully_confirm_their_email_address()
    {
        $this->withoutExceptionHandling();

        $this->post('/register', [
            'name'                  => 'John',
            'email'                 => 'john.doe@mail.com',
            'password'              => '12345678',
            'password_confirmation' => '12345678'
        ]);

        $user = User::whereName('John')->first();

        $this->assertNull($user->email_verified_at);
        $this->assertNotNull($user->confirmation_token);

        $this->get("/register/confirm?token={$user->confirmation_token}");

        $this->assertInstanceOf(Carbon::class, $user->fresh()->email_verified_at);
    }
}
