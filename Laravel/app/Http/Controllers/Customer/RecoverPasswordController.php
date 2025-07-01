<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\RecoverPasswordRequest;
use App\Mail\RecoverPasswordCustomer;
use App\Models\Customer;
use App\Models\Person;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RecoverPasswordController extends Controller
{
    public function show()
    {
        return view('customer.recover-password');
    }

    public function store(RecoverPasswordRequest $request)
    {
        $password = Str::random(8);

        $person = Person::where('email', $request->input('email'))->first();
        if (! $person) {
            return back()->withErrors(['email' => 'No existe un cliente registrado con ese email']);
        }

        $customer = Customer::where('person_id', $person->id)->first();
        if (! $customer) {
            return back()->withErrors(['email' => 'No existe un cliente registrado con ese email']);
        }

        $customer->update([
            'password' => Hash::make($password),
        ]);

        Mail::to($person->email)->send(
            new RecoverPasswordCustomer(
                $person->first_name,
                $person->last_name,
                $person->email,
                $password
            )
        );

        return redirect('/')->with(['toast' => 'info', 'message' => 'Se envio un correo a tu email con la nueva contraseña']);
    }
}
