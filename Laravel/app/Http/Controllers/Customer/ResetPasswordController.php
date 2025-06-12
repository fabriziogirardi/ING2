<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\ResetPasswordRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function show()
    {
        return view('customer.reset-password');
    }

    public function store(ResetPasswordRequest $request)
    {
        Auth::guard('customer')->user()->update([
            'password' => Hash::make($request->input('new_password')),
        ]);

        return redirect('/')->with(['toast' => 'success', 'message' => 'Contraseña actualizada']);
    }
}
