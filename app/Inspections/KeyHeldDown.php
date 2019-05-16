<?php

namespace App\Inspections;

use Exception;

final class KeyHeldDown
{
    public function detect($body)
    {
        if (preg_match('/(.)\\1{4,}/u', $body)) {
            throw new Exception('Your reply contains spam.');
        }
    }
}
