<?php
declare(strict_types=1);
session_start();

/* =============================================================
   PRÁCTICA: REGISTRO DE NOTAS DE UN GRUPO
   Técnicas de Programación para Internet - UES/FMO

   Temas que se ponen en práctica:
     1. Envío de formularios      -> method="post", $_POST, $_SERVER
     2. Arreglos                  -> indexados, asociativos y multidimensionales
     3. Condicionales             -> if / elseif / else y match
     4. Bucles                    -> foreach, for, while

   Un solo archivo. Guardarlo como registro_notas.php dentro de la
   carpeta del contenedor y abrirlo en http://localhost:8089/registro_notas.php
   ============================================================= */


/* -------------------------------------------------------------
   1. DATOS FIJOS EN ARREGLOS
   ------------------------------------------------------------- */

// Arreglo indexado: se recorrerá con foreach para pintar el <select>
$materias = ["Programación I", "Base de Datos II", "Técnicas de Programación para Internet"];

// Arreglo asociativo: clave => valor. La clave viaja en el formulario,
// el valor es lo que se muestra en pantalla.
$ponderaciones = [
    "parcial1" => "Primer parcial",
    "parcial2" => "Segundo parcial",
    "parcial3" => "Tercer parcial",
];

// Nota mínima de aprobación (constante: no cambia durante la ejecución)
const NOTA_MINIMA = 6.0;


/* -------------------------------------------------------------
   2. FUNCIONES DE APOYO
   ------------------------------------------------------------- */

/**
 * Calcula el promedio simple de un arreglo de notas.
 */
function promedio(array $notas): float
{
    if (count($notas) === 0) {
        return 0.0;
    }
    return array_sum($notas) / count($notas);
}

/**
 * Devuelve la condición del alumno usando match (PHP 8).
 * Compárelo en clase con la versión hecha con if/elseif/else.
 */
function condicion(float $promedio): string
{
    return match (true) {
        $promedio >= 9.0            => "Excelente",
        $promedio >= NOTA_MINIMA    => "Aprobado",
        default                     => "Reprobado",
    };
}

/**
 * Atajo para escapar la salida. NUNCA imprimir datos del usuario sin esto.
 */
function e(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, "UTF-8");
}


/* -------------------------------------------------------------
   3. ESTADO DE LA APLICACIÓN
   ------------------------------------------------------------- */

// Arreglo multidimensional: cada elemento es un arreglo asociativo
// con los datos de un alumno. Se guarda en sesión para que sobreviva
// entre envíos del formulario.
$_SESSION["registros"] ??= [];

$errores = [];   // arreglo indexado de mensajes de error
$exito   = "";   // mensaje de confirmación

// Botón "Vaciar lista": llega por GET, no por POST.
if (isset($_GET["vaciar"])) {
    $_SESSION["registros"] = [];
    header("Location: registro_notas.php");   // redirige para no reenviar el formulario
    exit;
}


/* -------------------------------------------------------------
   4. PROCESAMIENTO DEL FORMULARIO
   ------------------------------------------------------------- */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // ?? "" evita el aviso "undefined index" si el campo no llegó
    $nombre  = trim($_POST["nombre"]  ?? "");
    $carnet  = trim($_POST["carnet"]  ?? "");
    $materia = trim($_POST["materia"] ?? "");

    // $_POST["notas"] llega como ARREGLO porque los inputs se llaman notas[parcial1], etc.
    $notasCrudas = $_POST["notas"] ?? [];

    // --- Validaciones con condicionales ---
    if ($nombre === "") {
        $errores[] = "Escriba el nombre del alumno.";
    }

    if ($carnet === "") {
        $errores[] = "Escriba el carnet del alumno.";
    } elseif (strlen($carnet) < 6) {
        $errores[] = "El carnet debe tener al menos 6 caracteres.";
    }

    // in_array() comprueba que la materia enviada sea una de las permitidas.
    // Nunca confíe en un <select>: el usuario puede alterarlo desde el navegador.
    if (!in_array($materia, $materias, true)) {
        $errores[] = "Seleccione una materia válida.";
    }

    // --- Validación de las notas recorriendo el arreglo con foreach ---
    $notas = [];
    foreach ($ponderaciones as $clave => $etiqueta) {
        $valor = trim((string) ($notasCrudas[$clave] ?? ""));

        if ($valor === "") {
            $errores[] = "Falta la nota de $etiqueta.";
        } elseif (!is_numeric($valor)) {
            $errores[] = "La nota de $etiqueta debe ser un número.";
        } else {
            $nota = (float) $valor;                     // casting explícito
            if ($nota < 0 || $nota > 10) {
                $errores[] = "La nota de $etiqueta debe estar entre 0 y 10.";
            } else {
                $notas[$clave] = $nota;
            }
        }
    }

    // --- Si no hubo errores, se agrega el registro ---
    if (count($errores) === 0) {
        $prom = promedio($notas);

        $_SESSION["registros"][] = [
            "nombre"   => $nombre,
            "carnet"   => $carnet,
            "materia"  => $materia,
            "notas"    => $notas,          // arreglo dentro del arreglo
            "promedio" => $prom,
            "estado"   => condicion($prom),
        ];

        $exito = "Se registró a $nombre con promedio " . number_format($prom, 2) . ".";
    }
}


/* -------------------------------------------------------------
   5. ESTADÍSTICAS DEL GRUPO (bucles sobre el arreglo)
   ------------------------------------------------------------- */

$registros  = $_SESSION["registros"];
$total      = count($registros);
$aprobados  = 0;
$sumaProm   = 0.0;
$mejor      = null;

foreach ($registros as $r) {
    $sumaProm += $r["promedio"];

    if ($r["promedio"] >= NOTA_MINIMA) {
        $aprobados++;
    }

    // Guarda el registro con el promedio más alto
    if ($mejor === null || $r["promedio"] > $mejor["promedio"]) {
        $mejor = $r;
    }
}

$promedioGrupo = $total > 0 ? $sumaProm / $total : 0.0;
$reprobados    = $total - $aprobados;
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Registro de notas | UES-FMO</title>
<style>
    :root {
        --navy: #0e2841;
        --blue: #1e6fd0;
        --card: #edf1f7;
        --line: #d3dce8;
        --gray: #44546a;
        --ok:   #1b7a3d;
        --bad:  #b3261e;
    }
    * { box-sizing: border-box; }
    body {
        margin: 0;
        padding: 24px;
        font-family: Calibri, "Segoe UI", Arial, sans-serif;
        color: var(--gray);
        background: #f7f9fc;
        line-height: 1.5;
    }
    .contenedor { max-width: 1000px; margin: 0 auto; }
    h1 { color: var(--navy); font-size: 28px; margin: 0 0 4px; }
    h2 { color: var(--navy); font-size: 19px; margin: 28px 0 10px; }
    .sub { margin: 0 0 24px; font-style: italic; }

    form, .panel {
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 6px;
        padding: 20px;
    }
    .fila { display: flex; gap: 16px; flex-wrap: wrap; }
    .campo { flex: 1 1 220px; margin-bottom: 14px; }
    label { display: block; font-weight: bold; color: var(--navy); margin-bottom: 4px; font-size: 14px; }
    input, select {
        width: 100%; padding: 8px 10px; font-size: 15px;
        border: 1px solid var(--line); border-radius: 4px; font-family: inherit;
    }
    input:focus, select:focus { outline: 2px solid var(--blue); outline-offset: 1px; }

    button {
        background: var(--navy); color: #fff; border: 0; border-radius: 4px;
        padding: 10px 22px; font-size: 15px; font-family: inherit; cursor: pointer;
    }
    button:hover { background: var(--blue); }
    .enlace { color: var(--blue); font-size: 14px; margin-left: 14px; }

    .aviso { border-radius: 6px; padding: 12px 16px; margin-bottom: 18px; }
    .aviso ul { margin: 6px 0 0; padding-left: 20px; }
    .error { background: #fdecea; border: 1px solid #f3b9b4; color: var(--bad); }
    .bien  { background: #e8f5ec; border: 1px solid #a9d7ba; color: var(--ok); }

    table { width: 100%; border-collapse: collapse; margin-top: 8px; background: #fff; }
    th, td { padding: 8px 10px; border: 1px solid var(--line); text-align: left; font-size: 14px; }
    th { background: var(--navy); color: #fff; }
    tr.par td { background: #f4f7fb; }
    td.num { text-align: right; font-variant-numeric: tabular-nums; }
    .estado-aprobado, .estado-excelente { color: var(--ok); font-weight: bold; }
    .estado-reprobado { color: var(--bad); font-weight: bold; }

    .tarjetas { display: flex; gap: 14px; flex-wrap: wrap; }
    .tarjeta { flex: 1 1 160px; background: var(--card); border: 1px solid var(--line);
               border-radius: 6px; padding: 14px 16px; }
    .tarjeta .dato { font-size: 30px; font-weight: bold; color: var(--navy); }
    .tarjeta .rotulo { font-size: 13px; }
    .vacio { padding: 22px; text-align: center; background: var(--card);
             border: 1px dashed var(--line); border-radius: 6px; }
</style>
</head>
<body>
<div class="contenedor">

    <h1>Registro de notas</h1>
    <p class="sub">Formularios, arreglos, condicionales y bucles en un solo archivo PHP.</p>

    <?php /* ---- Mensajes: condicionales + bucle sobre el arreglo de errores ---- */ ?>
    <?php if (count($errores) > 0): ?>
        <div class="aviso error">
            <strong>Corrija lo siguiente antes de continuar:</strong>
            <ul>
                <?php foreach ($errores as $error): ?>
                    <li><?= e($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php elseif ($exito !== ""): ?>
        <div class="aviso bien"><?= e($exito) ?></div>
    <?php endif; ?>

    <?php /* ---- 1. FORMULARIO ---- */ ?>
    <form action="registro_notas.php" method="post">
        <div class="fila">
            <div class="campo">
                <label for="nombre">Nombre del alumno</label>
                <input type="text" id="nombre" name="nombre"
                       value="<?= e($_POST["nombre"] ?? "") ?>">
            </div>
            <div class="campo">
                <label for="carnet">Carnet</label>
                <input type="text" id="carnet" name="carnet"
                       value="<?= e($_POST["carnet"] ?? "") ?>">
            </div>
            <div class="campo">
                <label for="materia">Materia</label>
                <select id="materia" name="materia">
                    <option value="">-- Seleccione --</option>
                    <?php /* Bucle que genera las opciones desde el arreglo */ ?>
                    <?php foreach ($materias as $m): ?>
                        <option value="<?= e($m) ?>"
                            <?= (($_POST["materia"] ?? "") === $m) ? "selected" : "" ?>>
                            <?= e($m) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="fila">
            <?php /* Los tres campos comparten el nombre notas[], así llegan como arreglo */ ?>
            <?php foreach ($ponderaciones as $clave => $etiqueta): ?>
                <div class="campo">
                    <label for="<?= e($clave) ?>"><?= e($etiqueta) ?></label>
                    <input type="text" id="<?= e($clave) ?>" name="notas[<?= e($clave) ?>]"
                           value="<?= e((string) ($_POST["notas"][$clave] ?? "")) ?>"
                           placeholder="0 a 10">
                </div>
            <?php endforeach; ?>
        </div>

        <button type="submit">Registrar alumno</button>
        <?php if ($total > 0): ?>
            <a class="enlace" href="registro_notas.php?vaciar=1">Vaciar la lista</a>
        <?php endif; ?>
    </form>

    <?php /* ---- 2. RESULTADOS ---- */ ?>
    <h2>Alumnos registrados</h2>

    <?php if ($total === 0): ?>
        <p class="vacio">Todavía no hay registros. Complete el formulario para empezar.</p>
    <?php else: ?>

        <table>
            <tr>
                <th>#</th>
                <th>Alumno</th>
                <th>Carnet</th>
                <th>Materia</th>
                <?php foreach ($ponderaciones as $etiqueta): ?>
                    <th><?= e($etiqueta) ?></th>
                <?php endforeach; ?>
                <th>Promedio</th>
                <th>Condición</th>
            </tr>

            <?php /* foreach con índice: $i sirve para numerar y para pintar filas alternas */ ?>
            <?php foreach ($registros as $i => $r): ?>
                <tr class="<?= ($i % 2 === 1) ? "par" : "" ?>">
                    <td class="num"><?= $i + 1 ?></td>
                    <td><?= e($r["nombre"]) ?></td>
                    <td><?= e($r["carnet"]) ?></td>
                    <td><?= e($r["materia"]) ?></td>

                    <?php /* Bucle anidado: recorre las notas de este alumno */ ?>
                    <?php foreach ($r["notas"] as $nota): ?>
                        <td class="num"><?= number_format($nota, 1) ?></td>
                    <?php endforeach; ?>

                    <td class="num"><?= number_format($r["promedio"], 2) ?></td>
                    <td class="estado-<?= strtolower($r["estado"]) ?>"><?= e($r["estado"]) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h2>Resumen del grupo</h2>
        <div class="tarjetas">
            <div class="tarjeta">
                <div class="dato"><?= $total ?></div>
                <div class="rotulo">alumnos registrados</div>
            </div>
            <div class="tarjeta">
                <div class="dato"><?= $aprobados ?></div>
                <div class="rotulo">aprobados</div>
            </div>
            <div class="tarjeta">
                <div class="dato"><?= $reprobados ?></div>
                <div class="rotulo">reprobados</div>
            </div>
            <div class="tarjeta">
                <div class="dato"><?= number_format($promedioGrupo, 2) ?></div>
                <div class="rotulo">promedio del grupo</div>
            </div>
        </div>

        <?php if ($mejor !== null): ?>
            <p>
                Nota más alta: <strong><?= e($mejor["nombre"]) ?></strong>
                con <?= number_format($mejor["promedio"], 2) ?>.
            </p>
        <?php endif; ?>

        <h2>Escala de referencia</h2>
        <?php
        /* Bucle for clásico: genera la escala de 0 a 10 de dos en dos.
           Demuestra for + condicional dentro del mismo bloque. */
        ?>
        <table>
            <tr><th>Nota</th><th>Significado</th></tr>
            <?php for ($n = 10; $n >= 0; $n -= 2): ?>
                <tr>
                    <td class="num"><?= $n ?></td>
                    <td><?= e(condicion((float) $n)) ?></td>
                </tr>
            <?php endfor; ?>
        </table>

    <?php endif; ?>

</div>
</body>
</html>