<?php

namespace App\Http\Controllers\Employee\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\Auth\LoginRequest;
use App\Models\Branch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Show the login form for employees.
     */
    public function showLoginForm(): View
    {
        return view('employee.login', [
            'branches' => Branch::select('id', 'name')->get()->toArray(),
        ]);
    }

    public function loginAttempt(LoginRequest $request): RedirectResponse
    {
        if (! Auth::guard('employee')->attempt(['email' => $request->validated('email'), 'password' => $request->validated('password')])) {
            return redirect()->back()->withErrors([
                'credentials' => __('employee/auth.incorrect_credentials'),
            ]);
        }

        $request->session()->regenerate();
        $request->session()->put('branch_id', $request->branch_id);

        return redirect()->route('home');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('employee')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('employee.login');
    }
}
