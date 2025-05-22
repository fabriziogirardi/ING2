<?php

namespace App\Http\Controllers\Manager\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Auth\LoginRequest;
use App\Models\Manager;
use App\Models\ManagerToken;
use Random\RandomException;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @throws RandomException
     */
    public function __invoke(LoginRequest $request)
    {
        $manager = Manager::whereRelation('person', 'email', $request->email)->first();

        if (! $manager || ! password_verify($request->password, $manager->password)) {
            return redirect()->back()->withErrors([
                'incorrect_credentials' => __('manager.auth.incorrect_credentials'),
            ]);
        }

        ManagerToken::create([
            'manager_id' => $manager->id,
            'token'      => random_int(10000000, 99999999),
            'expires_at' => now()->addMinutes(2),
        ]);

        return redirect()->to(route('manager.verify-token'))->with([
            'email' => $request->email,
        ]);
    }
}
