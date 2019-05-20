<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RegisterConfirmationController extends Controller
{
    public function index(Request $request)
    {
        abort_if(!$request->has('token') || !$request->filled('token'), Response::HTTP_NOT_FOUND);

        $user = User::whereConfirmationToken($request->token)->first();

        if (!$user) {
            return redirect('/threads')
                ->withFlash('Unknown token.');
        }

        $user->markEmailAsVerified();

        return redirect('/threads')
            ->withFlash('Your account is now confirmed. You may post to the forum.');
    }
}
