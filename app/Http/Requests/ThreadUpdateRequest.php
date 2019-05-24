<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class ThreadUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('update', $this->thread);
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'body'  => 'required|string',
        ];
    }
}
