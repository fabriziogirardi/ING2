<?php

namespace App\Http\Controllers\Employee\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\Auth\LoginRequest;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request)
    {
        $employee = Employee::whereRelation('person', 'email', $request->validated('email'))->first();

        if (! $employee || ! password_verify($request->validated('password'), $employee->password)) {
            return redirect()->back()->withErrors([
                'incorrect_credentials' => __('employee/auth.incorrect_credentials'),
            ]);
        }

        Auth::guard('manager')->logout();
        Auth::guard('customer')->logout();

        Auth()->guard('employee')->login($employee);

        return redirect()->to(route('home'));
    }
}
