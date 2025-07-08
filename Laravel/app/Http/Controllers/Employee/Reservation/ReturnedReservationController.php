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

        // 1. Buscar el cliente (aunque esté soft deleted)
        $customer = Customer::withTrashed()
            ->whereHas('person', function ($query) use ($validated) {
                $query->where('government_id_number', $validated['government_id_number'])
                    ->where('government_id_type_id', $validated['government_id_type_id']);
            })
            ->with('person')
            ->first();

        if (! $customer) {
            return redirect()->back()->withErrors([
                'government_id_number' => 'No se encontró un cliente con el documento proporcionado.',
            ]);
        }

        // 2. Buscar la reserva por código y cliente
        $reservation = Reservation::where('code', $validated['code'])
            ->where('customer_id', $customer->id)
            ->with(['customer' => function ($query) {
                $query->withTrashed()->with('person');
            }])
            ->first();

        if (! $reservation) {
            return redirect()->back()->withErrors([
                'code' => 'El código ingresado no pertenece al cliente indicado.',
            ]);
        }

        // 3. Verificar que la reserva fue retirada
        if (! $reservation->retired) {
            return redirect()->back()->withErrors([
                'code' => 'Esta reserva aún no ha sido retirada. No se puede marcar como devuelta.',
            ]);
        }

        // 4. Verificar que la reserva no fue devuelta
        if ($reservation->returned) {
            return redirect()->back()->withErrors([
                'code' => 'Esta reserva ya fue devuelta anteriormente.',
            ]);
        }

        try {
            // Crear el registro de devolución
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
                'message' => 'Reserva marcada como devuelta correctamente.',
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Ocurrió un error al procesar la devolución. Intente nuevamente.',
            ]);
        }
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
