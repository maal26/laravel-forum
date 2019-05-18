<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserAvatarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image'
        ]);

        auth()->user()->update([
            'avatar_path' => $request->file('avatar')->store('avatars', 'public')
        ]);

        return back();
    }
}
