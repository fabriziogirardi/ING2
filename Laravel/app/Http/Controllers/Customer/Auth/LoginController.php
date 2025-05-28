<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Auth\LoginRequest;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request)
    {
        $customer = Customer::whereRelation('person', 'email', $request->validated('email'))->first();

        if (! $customer || ! password_verify($request->validated('password'), $customer->password)) {
            return redirect()->back()->withErrors([
                'incorrect_credentials' => __('customer/auth.incorrect_credentials'),
            ]);
        }

        Auth::guard('manager')->logout();
        Auth::guard('employee')->logout();

        Auth()->guard('customer')->login($customer);

        return redirect()->to(route('home'));
    }
}
