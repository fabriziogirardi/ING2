<?php

namespace App\Http\Controllers\Employee\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\Auth\LoginRequest;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\Person;
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
        $credentials = [
            'email' => $request->validated('email'),
            'password' => $request->validated('password'),
        ];

        $employee = Person::where('email', $credentials['email'])->first();

        if (! $employee) {
            return redirect()->back()->withErrors([
                'email' => 'No existe una persona registrada con ese email en el sistema',
            ]);
        }

        $employee = Employee::where('person_id', $employee->id)->first();

        if (! $employee) {
            return redirect()->back()->withErrors([
                'email' => 'La persona registrada con ese email no tiene una cuenta de empleado',
            ]);
        }

        if (! Auth::guard('employee')->attempt($credentials)) {
            return redirect()->back()->withErrors([
                'credentials' => 'Contraseña incorrecta',
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
