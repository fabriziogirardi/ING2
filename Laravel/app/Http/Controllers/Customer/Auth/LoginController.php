<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Auth\LoginRequest;
use App\Models\Customer;
use App\Models\Person;
use Illuminate\Http\RedirectResponse;
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
        $credentials = [
            'email' => $request->validated('email'),
            'password' => $request->validated('password'),
        ];

        $customer = Person::where('email', $credentials['email'])->first();

        if (! $customer) {
            return redirect()->back()->withErrors([
                'email' => 'No existe una persona registrada con ese email en el sistema',
            ]);
        }

        $customer = Customer::where('person_id', $customer->id)->first();

        if (! $customer) {
            return redirect()->back()->withErrors([
                'email' => 'La persona registrada con ese email no tiene una cuenta de cliente',
            ]);
        }

        if (! Auth::guard('customer')->attempt($credentials)) {
            return redirect()->back()->withErrors([
                'credentials' => 'Contraseña incorrecta',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->route('home');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('customer.login');
    }
}
