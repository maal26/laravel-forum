<?php

namespace App\Rules;

use GuzzleHttp\Client;
use Illuminate\Contracts\Validation\Rule;

class Recaptcha implements Rule
{
    private $remoteIp;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($remoteIp)
    {
        $this->remoteIp = $remoteIp;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $client = new Client();

        $response = $client->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
            'form_params' => [
                'secret'   => config('services.recaptcha.secret'),
                'response' => $value,
                'remoteip' => $this->remoteIp,
            ]
        ]);

        $decodedResponse = json_decode($response->getBody(), true);

        return $decodedResponse['success'];
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The recaptcha verification failed. Try again.';
    }
}
