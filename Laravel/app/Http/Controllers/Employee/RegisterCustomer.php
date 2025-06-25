<?php

namespace App\Http\Controllers\Employee;

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

class RegisterCustomer extends Controller
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
        $email = $request->validated('email');
        $govId = $request->validated('government_id_number');
        $govType = $request->validated('government_id_type_id');

        $personByEmail = Person::where('email', $email)->first();
        $personByGovId = Person::where('government_id_number', $govId)
            ->where('government_id_type_id', $govType)
            ->first();

        // If email exists but DNI/type does not match, error
        if ($personByEmail &&
            ($personByEmail->government_id_number !== $govId || $personByEmail->government_id_type_id != $govType)) {
            return back()->withErrors(['email' => 'Las credenciales ingresadas no son iguales a la persona existente.']);
        }

        // If DNI/type exists but email does not match, error
        if ($personByGovId && $personByGovId->email !== $email) {
            return back()->withErrors(['government_id_number' => 'Las credenciales ingresadas no son iguales a la persona existente.']);
        }

        $person = $personByEmail ?: $personByGovId;
        if (!$person) {
            $person = Person::create([
                'email'                 => $email,
                'government_id_number'  => $govId,
                'government_id_type_id' => $govType,
                'first_name'            => $request->validated('first_name'),
                'last_name'             => $request->validated('last_name'),
                'birth_date'            => $request->validated('birth_date'),
            ]);
        }

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

        return redirect('/')->with(['toast' => 'success', 'message' => 'Cliente registrado']);
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
