<?php

namespace App\Http\Controllers\Employee;


use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\CancelReservationRequest;
use App\Models\GovernmentIdType;
use App\Models\Product;
use App\Models\Reservation;
use Illuminate\Http\Request;

class CancelPolicyController extends Controller
{
    public function showInput()
    {
        $governmentIdType = GovernmentIdType::select('id', 'name')->get()->map(function ($governmentIdType) {
            return [
                'id'   => $governmentIdType->id,
                'name' => $governmentIdType->name,
            ];
        })->toArray();

        return view('employee.cancel-reservation.input-code', compact('governmentIdType'));
    }
    public function show(CancelReservationRequest $request)
    {
        $code = $request->input('code');
        $government_id_type_id = $request->input('government_id_type_id');
        $government_id_number = $request->input('government_id_number');

        $reservation = Reservation::where('code', $code)->first();

        if (!$reservation) {
            return redirect()->back()->withErrors(['error' => 'El codigo ingresado no pertenece a ninguna reserva']);
        }

        $customer = $reservation->customer->person;

        if (
            !$customer ||
            $customer->government_id_type_id != $government_id_type_id ||
            $customer->government_id_number != $government_id_number
        ) {
            return redirect()->back()->withErrors(['error' => 'La reserva no pertenece al cliente con el documento ingresado']);
        }

        $product    = $reservation->product;
        $policy     = $product->cancelPolicy;

        if (!$policy) {
            return view('employee.cancel-reservation.show', [
                'refund'  => 0,
                'message' => 'Política del producto: Nula',
                'product' => $product,
            ]);
        }

        if (!$policy->requires_amount_input) {
            return view('employee.cancel-reservation.show', [
                'refund'  => $reservation->total_amount,
                'message' => 'Política del producto: Completa',
                'product' => $product,
            ]);
        }

        return view('employee.cancel-reservation.input-parcial', [
            'message'  => 'Política del producto: Parcial',
            'product'  => $product,
            'maxValue' => $reservation->total_amount,
        ]);
    }

    public function handlePartial(Request $request)
    {
        $product = Product::findOrFail($request->get('product'), 'id');

        return view('employee.cancel-reservation.show', [
            'message'  => 'Política del producto: Parcial',
            'product'  => $product,
            'refund' => $request->input('refund_amount'),
        ]);
    }
}
