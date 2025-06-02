<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\RegisterCustomerRequest;
use App\Http\Requests\UpdatePersonRequest;
use App\Mail\NewCustomerCreated;
use App\Models\Customer;
use App\Models\GovernmentIdType;
use App\Models\Person;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function create()
    {
        $idTypes = GovernmentIdType::all();

        return view('customer.register', compact('idTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegisterCustomerRequest $request)
    {
        $person = Person::firstOrCreate([
            'email'                 => $request->validated('email'),
            'government_id_number'  => $request->validated('government_id_number'),
            'government_id_type_id' => $request->validated('government_id_type_id'),
        ], [
            'first_name' => $request->validated('first_name'),
            'last_name'  => $request->validated('last_name'),
            'birth_date' => $request->validated('birth_date'),
        ]);

        $password = Str::random(8);

        Customer::create([
            'person_id' => $person->id,
            'password'  => Hash::make($password),
        ]);

        Mail::to($person->email)->send(
            new NewCustomerCreated(
                $person->first_name,
                $person->last_name,
                $person->email,
                $password
            )
        );

        return redirect('/')->with('success', __('customer/auth.register_success'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Person $person)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Person $person)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePersonRequest $request, Person $person)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Person $person)
    {
        //
    }
}
