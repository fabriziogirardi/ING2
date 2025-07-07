<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\StoreRetiredReservationRequest;
use App\Http\Requests\UpdateRetiredReservationRequest;
use App\Models\GovernmentIdType;
use App\Models\Reservation;
use App\Models\RetiredReservation;

class RetiredReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRetiredReservationRequest $request)
    {
        $code                  = $request->input('code');
        $government_id_type_id = $request->input('government_id_type_id');
        $government_id_number  = $request->input('government_id_number');

        $reservation = Reservation::where('code', $code)->first();

        if (! $reservation) {
            return redirect()->back()->withErrors(['error' => 'El codigo ingresado no pertenece a ninguna reserva activa']);
        }

        if ($reservation->retired) {
            return redirect()->back()->withErrors(['error' => 'La reserva ya fue retirada']);
        }

        if ($reservation->start_date > now()->toDateString()) {
            return redirect()->back()->withErrors(['error' => 'Aun no puede retirarse la maquinaria, la reserva inicia la fecha ' . $reservation->start_date]);
        }

        $customer = $reservation->customer;

        if (method_exists($customer, 'trashed') && $customer->trashed()) {
            return redirect()->back()->withErrors(['error' => 'El cliente con ese documento de identidad esta bloqueado']);
        }

        $customer = $customer->person;

        if (
            ! $customer ||
            $customer->government_id_type_id != $government_id_type_id ||
            $customer->government_id_number != $government_id_number
        ) {
            return redirect()->back()->withErrors(['error' => 'La reserva no pertenece al cliente con el documento ingresado']);
        }

        $reservation->retired()->create();

        return redirect('/')->with(['toast' => 'success', 'message' => 'Reserva marcada como retirada']);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $governmentIdType = GovernmentIdType::select('id', 'name')->get()->map(function ($governmentIdType) {
            return [
                'id'   => $governmentIdType->id,
                'name' => $governmentIdType->name,
            ];
        })->toArray();

        return view('employee.retire-reservation', compact('governmentIdType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RetiredReservation $reservationRetired)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRetiredReservationRequest $request, RetiredReservation $reservationRetired)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RetiredReservation $reservationRetired)
    {
        //
    }
}
