<?php

namespace App\Http\Controllers\Manager\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Auth\VerifyTokenRequest;
use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyTokenController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(VerifyTokenRequest $request)
    {
        $manager = Manager::with('token')->whereRelation('person', 'email', $request->validated('email'))->first();
        if (! $manager) {
            return redirect()->back()->withErrors([
                'incorrect_credentials' => __('manager/auth.incorrect_credentials'),
            ]);
        }

        if (! $manager->token || $manager->token->token !== $request->validated('token') || $manager->token->expires_at < now()) {
            return redirect()->back()->withErrors([
                'incorrect_token' => __('manager/auth.incorrect_token'),
            ]);
        }

        Auth::guard('employee')->logout();
        Auth::guard('customer')->logout();

        Auth()->guard('manager')->login($manager);
        $manager->token->delete();

        return redirect()->to(route('filament.manager.pages.dashboard'));
    }
}
