<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('customer.login');
    }

    public function loginAttempt(LoginRequest $request): RedirectResponse
    {
        if (! Auth::guard('customer')->attempt(['email' => $request->validated('email'), 'password' => $request->validated('password')])) {
            return redirect()->back()->withErrors([
                'credentials' => __('customer/auth.incorrect_credentials'),
            ]);
        }

        $request->session()->regenerate();

        return redirect()->route('home')->with([
            'toast'   => 'success',
            'message' => __('customer/auth.login_successful'),
        ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
