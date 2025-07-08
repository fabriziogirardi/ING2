<?php

namespace App\Http\Controllers;

use App\Mail\NewReservationCreated;
use App\Models\BranchProduct;
use App\Models\Customer;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class BinanceController extends Controller
{
    public function showPaymentForm(Request $request)
    {
        $request->validate([
            'start_date'        => 'required|date',
            'end_date'          => 'required|date|after_or_equal:start_date',
            'customer_email'    => 'required|email',
            'branch_product_id' => 'required|exists:branch_product,id',
        ]);

        $start = Carbon::parse($request->start_date);
        $end   = Carbon::parse($request->end_date);
        $days  = $start->diffInDays($end) + 1;
        $email = $request->customer_email;

        // Buscar el customer para verificar penalización
        $customer = Customer::whereRelation('person', 'email', $email)->first();

        if (! $customer) {
            return back()->withErrors(['customer_email' => 'El correo no corresponde a ningún cliente registrado.'])->withInput();
        }

        $branchProduct = BranchProduct::findOrFail($request->branch_product_id);

        // Calcular el total base
        $baseTotal = $branchProduct->product->price * $days;

        // Aplicar recargo por penalización si corresponde
        $hasPenalization = $customer->has_penalization;
        $finalTotal      = $hasPenalization ? $baseTotal * 1.10 : $baseTotal;

        $code = Str::of(Str::random(8))->upper();

        while (Reservation::where('code', $code)->exists()) {
            $code = Str::of(Str::random(8))->upper();
        }
        session(['reservation_code' => $code]);

        return view('payment.binanceQR', [
            'product'         => $branchProduct->product,
            'branchProductId' => $branchProduct->id,
            'baseTotal'       => $baseTotal,
            'finalTotal'      => $finalTotal,
            'total'           => $finalTotal, // Para mantener compatibilidad
            'days'            => $days,
            'start'           => $start,
            'end'             => $end,
            'customer'        => $customer,
            'hasPenalization' => $hasPenalization,
        ]);
    }

    public function confirmPayment(Request $request)
    {
        $customer = Customer::whereRelation('person', 'email', $request->customer_email)->first();

        if (! $customer) {
            return back()->withErrors(['customer_email' => 'El correo no corresponde a ningún cliente registrado.'])->withInput();
        }

        // Recalcular el total para asegurar consistencia
        $start = Carbon::parse($request->start_date);
        $end   = Carbon::parse($request->end_date);
        $days  = $start->diffInDays($end) + 1;

        $branchProduct = BranchProduct::findOrFail($request->branch_product_id);
        $baseTotal     = $branchProduct->product->price * $days;

        // Verificar si el cliente tiene penalización y aplicar recargo del 10%
        $hasPenalization = $customer->has_penalization;
        $finalTotal      = $hasPenalization ? $baseTotal * 1.10 : $baseTotal;

        $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        $end_date   = Carbon::parse($request->end_date)->format('Y-m-d');

        $code = session('reservation_code');
        if (! $code) {
            return back()->withErrors(['error' => 'Invalid or expired reservation code.']);
        }
        session()->forget('reservation_code');

        if (Reservation::where('code', $code)->exists()) {
            return view('payment.ConfirmBinancePayment', [
                'code'            => $code,
                'baseTotal'       => $baseTotal,
                'finalTotal'      => $finalTotal,
                'hasPenalization' => $hasPenalization,
            ]);
        }

        Reservation::create([
            'customer_id'       => $customer->id,
            'branch_product_id' => $request->branch_product_id,
            'code'              => $code,
            'total_amount'      => $finalTotal,
            'start_date'        => $start_date,
            'end_date'          => $end_date,
        ]);

        $method = 'Binance';

        Mail::to($customer->person->email)->send(
            new NewReservationCreated(
                $code,
                $start_date,
                $finalTotal,
                $method,
            )
        );

        return view('payment.ConfirmBinancePayment', [
            'code'            => $code,
            'baseTotal'       => $baseTotal,
            'finalTotal'      => $finalTotal,
            'hasPenalization' => $hasPenalization,
        ]);
    }
}
