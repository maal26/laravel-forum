<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class RegisterConfirmationController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->has('token') && !$request->filled('token')) {
            return redirect('/login');
        }

        User::whereConfirmationToken($request->token)
            ->firstOrFail()
            ->markEmailAsVerified();

        return redirect('/threads');
    }
}
