<?php

namespace App\Http\Requests;

use App\Exceptions\ThrottleException;
use App\Reply;
use App\Rules\SpamFree;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class ReplyStoreRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('create', new Reply);
    }

    protected function failedAuthorization()
    {
        throw new ThrottleException(
            'You are replying too frequently. Please take a break.'
        );
    }

    public function rules()
    {
        return [
            'body' => ['required', 'string', new SpamFree],
        ];
    }
}
