<?php

namespace App;

class Spam
{
    public function detect($body)
    {
        $this->detectInvalidKeywords($body);

        return false;
    }

    protected function detectInvalidKeywords($body)
    {
        $invalidKeywords = collect([
            'yahoo customer support'
        ]);

        $invalidKeywords->each(function ($keyword) use ($body) {
            if (stripos($body, $keyword) !== false) {
                throw new \Exception('Your reply contains spam.');
            }
        });
    }
}
