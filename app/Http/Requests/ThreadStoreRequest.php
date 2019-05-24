<?php

namespace App\Http\Requests;

use App\Rules\Recaptcha;
use App\Rules\SpamFree;
use Illuminate\Foundation\Http\FormRequest;

class ThreadStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title'                => 'required|string|max:255',
            'body'                 => ['required', 'string', new SpamFree],
            'channel_id'           => 'required|exists:channels,id',
            'g-recaptcha-response' => ['required', new Recaptcha($this->ip())]
        ];
    }

    public function attributes()
    {
        return [
            'channel_id' => 'channel'
        ];
    }
}
