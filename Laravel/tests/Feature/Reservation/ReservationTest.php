<?php

namespace Tests\Feature\Reservation;

use App\Mail\NewReservationCreated;
use App\Models\Branch;
use App\Models\BranchProduct;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->customer = Customer::factory()->create();
    }

    public function test_customer_can_do_a_reservation()
    {
        $branch        = Branch::factory()->create();
        $product       = Product::factory()->create(['min_days' => 4]);
        $branchProduct = BranchProduct::create([
            'product_id' => $product->id,
            'branch_id'  => $branch->id,
            'quantity'   => 2,
        ]);

        Mail::fake();

        $this->actingAs($this->customer, 'customer');

        $response = $this->get(route('customer.reservation.store', [
            'branch_product_id' => $branchProduct->id,
            'customer_id'       => $this->customer->id,
            'start_date'        => '2026-08-07',
            'end_date'          => '2026-08-11',
            'code'              => Str::random(8),
            'total_amount'      => 100.00,
        ]));

        $response->assertViewIs('payment.success');

        Mail::assertSent(NewReservationCreated::class, function ($mail) {
            return $mail->hasTo($this->customer->person->email);
        });
    }
}
