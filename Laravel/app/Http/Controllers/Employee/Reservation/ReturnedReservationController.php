<?php

namespace App\Http\Controllers\Employee\Reservation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\StoreReturnedReservationRequest;
use App\Models\Customer;
use App\Models\GovernmentIdType;
use App\Models\Reservation;

class ReturnedReservationController extends Controller
{
    public function store(StoreReturnedReservationRequest $request)
    {
        $validated = $request->validated();

        $reservation = Reservation::whereHas('customer', function ($query) use ($validated) {
            $query->withTrashed()
                ->whereHas('person', function ($personQuery) use ($validated) {
                    $personQuery->where('government_id_number', $validated['government_id_number'])
                        ->where('government_id_type_id', $validated['government_id_type_id']);
                });
        })
            ->where('code', $validated['code'])
            ->whereHas('retired')
            ->whereDoesntHave('returned')
            ->with(['customer' => function ($query) {
                $query->withTrashed()->with('person');
            }])
            ->first();

        if (! $reservation) {
            return redirect()->back()->withErrors(['error' => 'Datos Incorrectos']);
        }

        $returned = $reservation->returned()->create([
            'rating'      => $validated['rating'],
            'description' => $validated['description'] ?? null,
        ]);

        // Solo actualizar el rating si el cliente no está eliminado
        if (! $reservation->customer->trashed()) {
            $this->updateCustomerRating($reservation->customer, $returned->rating);
        }

        return redirect()->back()->with([
            'toast'   => 'success',
            'message' => 'Reserva marcada como devuelta',
        ]);
    }

    public function show(Reservation $reservation)
    {
        $governmentIdType = GovernmentIdType::select('id', 'name')->get()->map(function ($governmentIdType) {
            return [
                'id'   => $governmentIdType->id,
                'name' => $governmentIdType->name,
            ];
        })->toArray();

        return view('employee.return-reservation', compact('governmentIdType'));
    }

    private function updateCustomerRating(Customer $customer, int $newRating): void
    {
        $count    = $customer->reservations_count;
        $newCount = $count + 1;

        $calculatedAverage = (($customer->rating * $count) + $newRating) / $newCount;
        $average           = max(0, min(5, $calculatedAverage));

        $customer->update([
            'rating'             => $average,
            'reservations_count' => $newCount,
        ]);

        if ($average < 1) {
            $customer->delete(); // Bloqueo de cuenta del cliente

            return;
        }
    }
}
