 <!DOCTYPE html>
<html>
<head>
    <title>Pagando con Mercado Pago</title>
</head>
<body>

<div id="walletBrick_container"></div>

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

</body>
</html>

