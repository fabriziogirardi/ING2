<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('customer.login');
    }

    public function loginAttempt(LoginRequest $request)
    {
        if (! Auth::guard('customer')->attempt(['email' => $request->validated('email'), 'password' => $request->validated('password')])) {
            return redirect()->back()->withErrors([
                'credentials' => __('customer/auth.incorrect_credentials'),
            ]);
        }

        $request->session()->regenerate();

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('customer.login');
    }
}
