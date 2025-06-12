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
        $reservation = Reservation::whereRelation('customer.person', 'government_id_number', $request->validated('government_id_number'))
            ->whereRelation('customer.person', 'government_id_type_id', $request->validated('government_id_type_id'))
            ->whereDoesntHave('retired')
            ->where('code', $request->validated('code'))
            ->first();

        if (! $reservation) {
            return redirect()->back()->withErrors(['error' => 'Datos Incorrectos']);
        }

        $reservation->retired()->create();

        return redirect()->back()->with(['toast' => 'success', 'message' => 'Reserva marcada como retirada']);
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
