<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\ResetPasswordRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function __invoke(ResetPasswordRequest $request)
    {
        Auth::guard('customer')->user()->update([
            'password' => Hash::make($request->input('new_password')),
        ]);

        return redirect('/')->with('success', __('customer/auth.reset_password_success'));
    }
}
