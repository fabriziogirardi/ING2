<?php

namespace App\Http\Controllers\Reservation;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateReservationRequest;
use App\Mail\NewReservationCreated;
use App\Models\BranchProduct;
use App\Models\Customer;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ReservationController extends Controller
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
    public function store(Request $request, BranchProduct $branch_product_id, Customer $customer_id, $start_date, $end_date, $code, $total_amount)
    {
        if (! $request->hasValidRelativeSignatureWhileIgnoring([
            'collection_id',
            'collection_status',
            'payment_id',
            'status',
            'external_reference',
            'payment_type',
            'merchant_order_id',
            'preference_id',
            'site_id',
            'processing_mode',
            'merchant_account_id',
        ])) {

            $code = Str::of(Str::random(8))->upper();
        }

        if (Reservation::where('code', $code)->exists()) {
            return view('payment.success')->with('success', __('reservation/reservation.created'));
        }

        $user = auth()->user();

        $method = 'Mercado Pago';

        Reservation::create([
            'customer_id'       => $customer_id->id,
            'branch_product_id' => $branch_product_id->id,
            'code'              => $code,
            'total_amount'      => $total_amount,
            'start_date'        => $start_date,
            'end_date'          => $end_date,
        ]);

        Mail::to($user->person->email)->send(
            new NewReservationCreated(
                $code,
                $request->start_date,
                $total_amount,
                $method,
            )
        );

        return view('payment.success')->with('success', __('reservation/reservation.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        //
    }
}
