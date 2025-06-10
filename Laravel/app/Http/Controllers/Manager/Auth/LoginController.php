<?php

namespace App\Http\Controllers\Manager\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Auth\LoginRequest;
use App\Http\Requests\Manager\Auth\TokenRequest;
use App\Mail\Manager\TokenGeneratedMail;
use App\Models\Manager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Mail;
use Random\RandomException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('manager.login');
    }

    /**
     * @throws RandomException
     */
    public function loginAttempt(LoginRequest $request): RedirectResponse
    {
        $manager = Manager::whereRelation('person', 'email', $request->validated('email'))->first();

        if (! $manager || ! password_verify($request->validated('password'), $manager->password)) {
            return redirect()->back()->withErrors([
                'credentials' => __('manager/auth.incorrect_credentials'),
            ]);
        }

        $manager->createToken();

        Mail::to($request->email)->send(new TokenGeneratedMail($manager->token));

        $signedRoute = URL::temporarySignedRoute(
            'manager.verify-token',
            now()->addMinutes(2),
            ['manager' => $manager->id]
        );

        return redirect()->to($signedRoute);
    }

    public function logout(Request $request)
    {
        Auth::guard('manager')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('manager.login');
    }

    public function showTokenForm(Request $request, Manager $manager)
    {
        return view('manager.verify-token', ['manager_id' => $manager->id]);
    }

    public function tokenAttempt(TokenRequest $request, Manager $manager): RedirectResponse
    {
        $manager->load('token');

        if (! $manager->token || $manager->token->token !== $request->validated('token')) {
            return redirect()->back()->withErrors([
                'invalid_token' => __('manager/auth.incorrect_token'),
            ]);
        }

        if ($manager->token->expires_at < now()) {
            return redirect()->route('manager.login')->withErrors([
                'credentials' => __('manager/auth.expired_token'),
            ]);
        }

        $manager->deleteTokens();
        Auth::guard('manager')->login($manager);

        return redirect()->route('filament.manager.pages.dashboard');
    }
}
