@php
    $labels = [
        'branch_product_id' => __('reservation/forms.mp_payment_label_branch_product'),
        'start_date'        => __('reservation/forms.mp_payment_label_start_date'),
        'end_date'          => __('reservation/forms.mp_payment_label_end_date'),
        'total_amount'        => __('reservation/forms.mp_payment_label_unit_price'),
    ];
@endphp

<x-layouts.app>
    <x-slot:title>
        {{ __('reservation/forms.mp_payment_title_slot') }}
    </x-slot:title>

    <div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900">
        <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                {{ __('reservation/forms.mp_payment_heading') }}
            </h5>
            <div class="mb-4">
                <p><strong>{{ $labels['branch_product_id'] }}:</strong> {{ $productName }}</p>
                @foreach($requestData as $key => $value)
                    @if($key !== 'branch_product_id')
                        <p><strong>{{ $labels[$key] ?? $key }}:</strong> {{ $value }}</p>
                    @endif
                @endforeach
            </div>
            <div id="walletBrick_container"></div>
        </div>
    </div>

    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <script>
        const publicKey = "{{ $publicKey }}";
        const preferenceId = "{{ $preferenceId }}";

        const mp = new MercadoPago(publicKey);

        const bricksBuilder = mp.bricks();
        const renderWalletBrick = async (bricksBuilder) => {
            await bricksBuilder.create("wallet", "walletBrick_container", {
                initialization: {
                    preferenceId: preferenceId,
                }
            });
        };

        renderWalletBrick(bricksBuilder);
    </script>
</x-layouts.app>

