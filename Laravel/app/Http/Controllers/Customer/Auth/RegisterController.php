<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Customer\RegisterCustomerRequest;
use App\Http\Requests\StorePersonRequest;
use App\Http\Requests\UpdatePersonRequest;
use App\Models\Customer;
use App\Models\Person;

class RegisterController extends Controller
{
    public function create()
    {
        return view('customer.register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegisterCustomerRequest $request)
    {
        $person = Person::firstOrCreate([
            'email'                 => $request->validated('email'),
            'government_id_type_id' => 1,
            //Falta saber si es necesario government id number
            'government_id_number'  => $request->validated('government_id_number'),
        ], [
            'first_name' => $request->validated('first_name'),
            'last_name'  => $request->validated('last_name'),
        ]);

        $request -> validated('birth_date');

        Customer::create([
            'person_id' => $person->id,
            'password'   => bcrypt($request->validated('password')),
        ]);

        return redirect('/');
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
