<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function signIn(? User $user = null, array $overrides = [])
    {
        $user = $user ?? factory(User::class)->create($overrides);

        return $this->actingAs($user);
    }
}
