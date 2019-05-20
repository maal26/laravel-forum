<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class RegisterConfirmationController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->has('token')) {
            return redirect('/login');
        }

        try {
            User::whereConfirmationToken($request->token)
            ->firstOrFail()
            ->markEmailAsVerified();
        } catch (ModelNotFoundException $e) {
            return redirect('/threads')
                ->withFlash('Unknown token.');
        }

        return redirect('/threads')
            ->withFlash('Your account is now confirmed. You may post to the forum.');
    }
}
