<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserAvatarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $this->authorize('update', $request->user());

        $request->validate([
            'avatar' => 'required|image'
        ]);

        auth()->user()->update([
            'avatar_path' => $request->file('avatar')->store('avatars', 'public')
        ]);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
