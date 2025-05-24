<?php

namespace App\Http\Controllers\Manager\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Auth\LoginRequest;
use App\Mail\Manager\TokenGeneratedMail;
use App\Models\Manager;
use Mail;
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
        $manager = Manager::whereRelation('person', 'email', $request->validated('email'))->first();

        if (! $manager || ! password_verify($request->validated('password'), $manager->password)) {
            return redirect()->back()->withErrors([
                'incorrect_credentials' => __('manager/auth.incorrect_credentials'),
            ]);
        }

        $manager->createToken();

        Mail::to($request->email)->send(new TokenGeneratedMail($manager->token));

        return redirect()->to(route('manager.verify-token'))->with([
            'email' => $request->validated('email'),
        ]);
    }
}
