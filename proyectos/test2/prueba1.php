<?php

session_start();

$_SESSION["Registro"] ??= [];
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Validación del lado del servidor
    $nombre = trim($_POST["nombre_estudiante"] ?? "");
    $carnet = trim($_POST["carnet_estudiante"] ?? "");
    $notas  = $_POST["notas"] ?? [];

    if ($nombre === "" || $carnet === "" || count($notas) < 3) {
        $error = "Todos los campos son obligatorios.";
    } else {
        $_SESSION['Registro'][] = [
            "nombre" => $nombre,
            "carnet" => $carnet,
            "notas"  => $notas,
        ];

        header("Location: " . $_SERVER["PHP_SELF"]);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de estudiantes</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            display: grid;
            grid-template-columns: auto;
            justify-content: center;
            font-family: Georgia, 'Times New Roman', serif;
            background-color: #f4f2ee;
            color: #2b2b2b;
            margin: 0;
            padding: 40px 20px;
        }

        h1 {
            text-align: center;
            font-family: Georgia, 'Times New Roman', serif;
            color: #1f2d3d;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .ContenedorFormulario {
            display: grid;
            justify-content: center;
            text-align: center;
            width: 560px;
            background-color: #ffffff;
            border: 1px solid #d8d2c4;
            border-radius: 6px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
            padding: 30px 35px;
        }

        .ContenedorFormulario h1 {
            font-size: 24px;
            margin-top: 0;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #1f2d3d;
        }

        .formulario {
            display: grid;
            grid-template-columns: auto;
            text-align: left;
        }

        label {
            font-size: 14px;
            font-weight: bold;
            color: #444;
            margin-top: 12px;
            margin-bottom: 4px;
        }

        input {
            width: 100%;
            height: 32px;
            padding: 4px 8px;
            font-family: inherit;
            font-size: 14px;
            border: 1px solid #b9b2a3;
            border-radius: 4px;
            background-color: #fbfaf7;
        }

        input:focus {
            outline: none;
            border-color: #1f2d3d;
            background-color: #ffffff;
        }

        table {
            border-collapse: collapse;
            margin: 20px auto 10px auto;
            width: 100%;
            max-width: 700px;
            background-color: #ffffff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        th, td {
            padding: 10px 16px;
            border: 1px solid #d8d2c4;
            text-align: center;
            font-size: 14px;
        }

        th {
            background-color: #1f2d3d;
            color: #ffffff;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }

        tr:nth-child(even) td {
            background-color: #f7f5f0;
        }

        tr:hover td {
            background-color: #ece7db;
        }

        .error {
            color: #a12020;
            background-color: #fbeaea;
            border: 1px solid #e3b3b3;
            border-radius: 4px;
            padding: 8px 12px;
            font-size: 14px;
        }

        .btnEnviarRegistro {
            margin-top: 20px;
            padding: 8px 0;
            border: none;
            border-radius: 4px;
            height: 38px;
            width: 45%;
            background-color: #1f2d3d;
            color: white;
            font-family: inherit;
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 0.5px;
            cursor: pointer;
            justify-self: center;
        }

        .btnEnviarRegistro:hover {
            background-color: #33465e;
        }

        .btnVaciarRegistro {
            margin-top: 10px;
            padding: 8px 16px;
            border: 1px solid #a12020;
            border-radius: 4px;
            background-color: #ffffff;
            color: #a12020;
            font-family: inherit;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
        }

        .btnVaciarRegistro:hover {
            background-color: #a12020;
            color: #ffffff;
        }

        .SeccionTabla {
            display: grid;
            justify-content: center;
            text-align: center;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<div class="ContenedorFormulario">
    <h1>Registro de estudiantes</h1>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form class="formulario" action="" method="POST">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre_estudiante" required>

        <label for="carnet">Carnet</label>
        <input type="text" id="carnet" name="carnet_estudiante" required>

        <label for="nota1">Nota 1</label>
        <input type="text" id="nota1" name="notas[]" required>

        <label for="nota2">Nota 2</label>
        <input type="text" id="nota2" name="notas[]" required>

        <label for="nota3">Nota 3</label>
        <input type="text" id="nota3" name="notas[]" required>

        <button class="btnEnviarRegistro" type="submit">Enviar</button>
    </form>
</div>

<div class="SeccionTabla">
    <h1>Estudiantes registrados</h1>
    <table>
    <tr>
        <th>Nombre</th>
        <th>Carnet</th>
        <th>Nota 1</th>
        <th>Nota 2</th>
        <th>Nota 3</th>
        <th>Promedio</th>
    </tr>

    <?php foreach ($_SESSION['Registro'] as $estudiante): ?>
        <?php
            $notasEstudiante = $estudiante['notas'];
            $promedio = array_sum($notasEstudiante) / count($notasEstudiante);
        ?>
        <tr>
            <td><?= $estudiante['nombre'] ?></td>
            <td><?= $estudiante['carnet'] ?></td>
            <td><?= $estudiante['notas'][0] ?></td>
            <td><?= $estudiante['notas'][1] ?></td>
            <td><?= $estudiante['notas'][2] ?></td>
            <td><?= number_format($promedio, 2) ?></td>
        </tr>
    <?php endforeach; ?>
    </table>

    
</div>

</body>
</html>