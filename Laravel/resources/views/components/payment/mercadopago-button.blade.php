<div>
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <script>
        const mp = new MercadoPago({{ $publicKey }});

        const bricksBuilder = mp.bricks();
        const renderWalletBrick = async (bricksBuilder) => {
            await bricksBuilder.create("wallet", "walletBrick_container", {
                initialization: {
                    preferenceId: {{ $preferenceId }},
                }
            });
        };

        renderWalletBrick(bricksBuilder);
    </script>
</div>
