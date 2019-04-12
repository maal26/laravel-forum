<?php

namespace App\Http\Requests;

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
            'title'      => 'required|string|max:255',
            'body'       => 'required|string',
            'user_id'    => 'required|exists:users,id',
            'channel_id' => 'required|exists:channels,id'
        ];
    }
}
