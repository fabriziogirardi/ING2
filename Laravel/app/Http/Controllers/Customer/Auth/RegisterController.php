<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\RegisterCustomerRequest;
use App\Http\Requests\UpdatePersonRequest;
use App\Mail\NewCustomerCreated;
use App\Models\Customer;
use App\Models\Person;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\GovernmentIdType;

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
            'government_id_type_id' => $request->validated('government_id_type_id'),
            'government_id_number'  => $request->validated('government_id_number'),
        ], [
            'first_name' => $request->validated('first_name'),
            'last_name'  => $request->validated('last_name'),
        ]);

        //Se valida que sea mayor de edad
        $request -> validated('birth_date');

        //Se crea una contraseña aleatoria para enviarla al mail del cliente
        $password = Str::random(8);
        //Se crea un nuevo cliente y se general la contraseña
        Customer::create([
            'person_id'  => $person->id,
            'password'   => bcrypt($password),
        ]);

        //Se envia un correo al cliente con sus datos
        Mail::to($person->email)->send(
            new NewCustomerCreated(
                $person->first_name,
                $person->last_name,
                $person->email,
                $password
            )
        );

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
