<?php

namespace App\Http\Controllers;

use App\Models\BranchProduct;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BinanceController extends Controller
{
    public function showPaymentForm(Request $request)
    {
        $start = Carbon::parse($request->start_date);
        $end   = Carbon::parse($request->end_date);
        $days  = $start->diffInDays($end) + 1;

        $branchProduct = BranchProduct::findOrFail($request->branch_product_id);
        $total         = $branchProduct->product->price * $days;

        return view('payment.binanceQR', [
            'product'         => $branchProduct->product,
            'branchProductId' => $branchProduct->id,
            'total'           => $total,
            'days'            => $days,
            'start'           => $start,
            'end'             => $end,
        ]);
    }

    public function confirmPayment(Request $request)
    {
        $code = Str::of(Str::random(8))->upper();

        $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        $end_date   = Carbon::parse($request->end_date)->format('Y-m-d');

        Reservation::create([
            'customer_id'       => auth('employee')->id(),
            'branch_product_id' => $request->branch_product_id,
            'code'              => $code,
            'start_date'        => $start_date,
            'end_date'          => $end_date,
        ]);

        return view('payment.ConfirmBinancePayment', [
            'code' => $code,
        ]);
    }
}
