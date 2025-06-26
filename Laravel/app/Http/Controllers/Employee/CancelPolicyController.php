<?php

namespace App\Http\Controllers\Employee;


use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Reservation;
use Illuminate\Http\Request;

class CancelPolicyController extends Controller
{
    public function show(Request $request)
    {
        $code = $request->input('code');

        $reservation = Reservation::where('code', $code)
            ->first();

        if (!$reservation) {
            return redirect()->back()->withErrors(['error' => 'Datos Incorrectos']);
        }

        $product    = $reservation->product;
        $policy     = $product->cancelPolicies->first();

        if (!$policy) {
            return view('employee.cancel-reservation.show', [
                'refund'  => 0,
                'message' => 'Política del producto: Nula',
                'product' => $product,
            ]);
        }

        if (!$policy->requires_amount_input) {
            return view('employee.cancel-reservation.show', [
                'refund'  => $product->price,
                'message' => 'Política del producto: Completa',
                'product' => $product,
            ]);
        }

        return view('employee.cancel-reservation.input-parcial', [
            'message'  => 'Política del producto: Parcial',
            'product'  => $product,
            'maxValue' => $product->price,
        ]);
    }

    public function handlePartial(Product $product, string $code, $price)
    {
        return view('employee.cancel-reservation.show', [
            'message'  => 'Política del producto: Parcial',
            'product'  => $product,
            'maxValue' => $price,
        ]);
    }
}
