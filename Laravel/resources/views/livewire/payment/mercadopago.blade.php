<div class="flex flex-wrap">
    <label for="branch_product_id" class="block mb-2 text-sm font-medium text-gray-900"></label>
    <select wire:model.live="branchProductId" wire:change="generateButton" id="branch_product_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
        <option value="0" selected disabled>Seleccionar sucursal de retiro</option>
        @foreach($branchesWithStock as $branch_id => $branch_name)
            <option value="{{ $branch_id }}">
                {{ $branch_name }}
            </option>
        @endforeach
    </select>
    <div id="walletBrick_container"></div>
    <div>
        <script src="https://sdk.mercadopago.com/js/v2"></script>
        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('algo', (event) => {
                    const publicKey = event[0].publicKey;
                    const preferenceId = event[0].preferenceId;

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
                });
            });
        </script>
    </div>
{{--    <x-payment.mercadopago-button :publicKey="$publicKey" :preferenceId="$preferenceId" />--}}
    @if($branchProductId)
    @else
    @endif
</div>
