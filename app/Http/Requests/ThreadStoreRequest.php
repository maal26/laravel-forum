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
            'channel_id' => 'required|exists:channels,id'
        ];
    }

    public function attributes()
    {
        return [
            'channel_id' => 'channel'
        ];
    }
}
