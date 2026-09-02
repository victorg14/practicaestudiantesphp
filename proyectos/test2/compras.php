<?php
// ============================================================
// Ejercicio 3: Tabla de compras con IVA, descuento por volumen,
// cupón de descuento y costo de envío condicional
// ============================================================

// Arreglo de compras ya definido (producto, precio sin IVA, cantidad)
$compras = [
    ['producto' => 'Camisa',   'precio' => 15.00, 'cantidad' => 3],
    ['producto' => 'Pantalón', 'precio' => 25.00, 'cantidad' => 6],
    ['producto' => 'Zapatos',  'precio' => 40.00, 'cantidad' => 1],
    ['producto' => 'Gorra',    'precio' => 8.50,  'cantidad' => 5],
];

const IVA_PORCENTAJE          = 0.13;
const DESCUENTO_VOLUMEN       = 0.05; // 5% si cantidad >= 5 en una fila
const CANTIDAD_MINIMA_VOLUMEN = 5;
const DESCUENTO_CUPON_AHORRO10 = 0.10; // 10% sobre el subtotal general
const COSTO_ENVIO             = 2.99;
const UMBRAL_ENVIO_GRATIS     = 25.00;

// Cupón ingresado por el usuario (GET o POST)
$cupon = isset($_GET['cupon']) ? strtoupper(trim($_GET['cupon'])) : '';

// -----------------------------------------------------------
// 1. Calcular subtotal por fila (aplicando descuento por volumen)
// -----------------------------------------------------------
$filas = [];
$subtotalGeneral = 0;

foreach ($compras as $item) {
    $subtotalFila = $item['precio'] * $item['cantidad'];
    $tieneDescuentoVolumen = $item['cantidad'] >= CANTIDAD_MINIMA_VOLUMEN;

    if ($tieneDescuentoVolumen) {
        $subtotalFila -= $subtotalFila * DESCUENTO_VOLUMEN;
    }

    $filas[] = [
        'producto'  => $item['producto'],
        'precio'    => $item['precio'],
        'cantidad'  => $item['cantidad'],
        'subtotal'  => $subtotalFila,
        'descuento' => $tieneDescuentoVolumen,
    ];

    $subtotalGeneral += $subtotalFila;
}

// -----------------------------------------------------------
// 2. Aplicar cupón (si corresponde) sobre el subtotal general,
//    antes de calcular el IVA
// -----------------------------------------------------------
$descuentoCupon = 0;
$envioGratisPorCupon = false;

if ($cupon === 'AHORRO10') {
    $descuentoCupon = $subtotalGeneral * DESCUENTO_CUPON_AHORRO10;
} elseif ($cupon === 'ENVIOGRATIS') {
    $envioGratisPorCupon = true;
}

$subtotalConCupon = $subtotalGeneral - $descuentoCupon;

// -----------------------------------------------------------
// 3. Calcular IVA sobre el subtotal ya con el cupón aplicado
// -----------------------------------------------------------
$iva = $subtotalConCupon * IVA_PORCENTAJE;

// -----------------------------------------------------------
// 4. Costo de envío: se cobra solo si el subtotal tras descuentos
//    es menor a $25, salvo que el cupón sea ENVIOGRATIS
// -----------------------------------------------------------
$costoEnvio = 0;
if (!$envioGratisPorCupon && $subtotalConCupon < UMBRAL_ENVIO_GRATIS) {
    $costoEnvio = COSTO_ENVIO;
}

// -----------------------------------------------------------
// 5. Total a pagar
// -----------------------------------------------------------
$total = $subtotalConCupon + $iva + $costoEnvio;

function formatoMoneda($valor)
{
    return '$' . number_format($valor, 2);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resumen de Compras</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f6f7fb; padding: 30px; }
        .contenedor { max-width: 700px; margin: 0 auto; background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
        h1 { color: #333; text-align: center; }
        form.cupon { display: flex; gap: 10px; justify-content: center; margin-bottom: 25px; }
        form.cupon input { padding: 8px; border: 1px solid #ccc; border-radius: 5px; }
        form.cupon button { padding: 8px 16px; background: #444; color: #fff; border: none; border-radius: 5px; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 10px; border-bottom: 1px solid #eee; text-align: left; }
        th { background: #333; color: #fff; }
        td.numero, th.numero { text-align: right; }
        .badge { font-size: .75em; background: #ffe08a; color: #7a5b00; padding: 2px 6px; border-radius: 4px; margin-left: 5px; }
        .resumen { border-top: 2px solid #333; padding-top: 15px; }
        .resumen p { display: flex; justify-content: space-between; margin: 6px 0; }
        .resumen .total { font-size: 1.2em; font-weight: bold; color: #22577a; }
        .resumen .descuento { color: #a30000; }
    </style>
</head>
<body>
<div class="contenedor">
    <h1>Resumen de Compras</h1>

    <form class="cupon" method="get" action="">
        <input type="text" name="cupon" placeholder="Código de cupón (AHORRO10 / ENVIOGRATIS)"
               value="<?php echo htmlspecialchars($cupon); ?>" size="35">
        <button type="submit">Aplicar cupón</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th class="numero">Precio</th>
                <th class="numero">Cantidad</th>
                <th class="numero">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($filas as $fila): ?>
                <tr>
                    <td>
                        <?php echo htmlspecialchars($fila['producto']); ?>
                        <?php if ($fila['descuento']): ?>
                            <span class="badge">-5% vol.</span>
                        <?php endif; ?>
                    </td>
                    <td class="numero"><?php echo formatoMoneda($fila['precio']); ?></td>
                    <td class="numero"><?php echo $fila['cantidad']; ?></td>
                    <td class="numero"><?php echo formatoMoneda($fila['subtotal']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="resumen">
        <p><span>Subtotal (con descuento por volumen aplicado)</span> <span><?php echo formatoMoneda($subtotalGeneral); ?></span></p>

        <?php if ($descuentoCupon > 0): ?>
            <p class="descuento"><span>Descuento cupón AHORRO10 (10%)</span> <span>-<?php echo formatoMoneda($descuentoCupon); ?></span></p>
        <?php endif; ?>

        <p><span>Subtotal tras cupón</span> <span><?php echo formatoMoneda($subtotalConCupon); ?></span></p>
        <p><span>IVA (13%)</span> <span><?php echo formatoMoneda($iva); ?></span></p>

        <p>
            <span>Envío
                <?php if ($envioGratisPorCupon): ?>
                    <span class="badge">Gratis (cupón)</span>
                <?php elseif ($costoEnvio == 0): ?>
                    <span class="badge">Gratis (subtotal ≥ $<?php echo number_format(UMBRAL_ENVIO_GRATIS, 2); ?>)</span>
                <?php endif; ?>
            </span>
            <span><?php echo formatoMoneda($costoEnvio); ?></span>
        </p>

        <p class="total"><span>Total a pagar</span> <span><?php echo formatoMoneda($total); ?></span></p>
    </div>
</div>
</body>
</html>