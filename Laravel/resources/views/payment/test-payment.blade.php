<x-layouts.app>
    <x-slot:title>
        Login
    </x-slot:title>

    <form method="GET" action="{{ route('customer.payment') }}">
        @csrf
        <label>Branch Product ID: <input name="branch_product_id" type="number" required></label><br>
        <label>Start Date: <input name="start_date" type="date" required></label><br>
        <label>End Date: <input name="end_date" type="date" required></label><br>
        <label>Total Amount: <input name="total_amount" type="number" step="0.01" required></label><br>
        <button type="submit">Test Payment</button>
    </form>
</x-layouts.app>
