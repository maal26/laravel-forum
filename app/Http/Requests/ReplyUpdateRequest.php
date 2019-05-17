<?php

namespace App\Http\Requests;

use App\Rules\SpamFree;
use Illuminate\Foundation\Http\FormRequest;

class ReplyUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->id === $this->reply->owner->id;
    }

    public function rules()
    {
        return [
            'body' => ['required', 'string', new SpamFree]
        ];
    }
}
