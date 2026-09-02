<?php

session_start();

$_SESSION["registro"] ??= [];

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $nombre = trim($_POST["nombre_estudiante"]);
    $carnet =trim($_POST["carnet_estudiante"]);
    $notas = $_POST["notas"]??[];

    $_SESSION['registro'][] =[
        "nombre" => $nombre,
        "carnet" => $carnet,
        "notas" => $notas
    ];

    header("Location: " . $_SERVER["PHP_SELF"]);
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<div class="contenedor_formulario">
    <h1>Registro Estudiantes</h1>

    <form action="" method="post">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre_estudiante" required>

        <label for="nombre">Carnet</label>
        <input type="text" id="nombre" name="carnet_estudiante" required>

        <label for="nombre">Nota 1</label>
        <input type="text" id="nombre" name="notas[]" required>

        <label for="nombre">Nota 2</label>
        <input type="text" id="nombre" name="notas[]" required>

        <label for="nombre">Nota 3</label>
        <input type="text" id="nombre" name="notas[]" required>

        <button class="btnGuardarDatos" type="submit">Guardar Datos</button>


    </form>
</div>

<div class="tabla_datos_registrados">

<h1>Estudiantes Registrados</h1>

<Table>
    <tr>
        <th>Nombre</th>
        <th>Carnet</th>
        <th>Nota 1</th>
        <th>Nota 2</th>
        <th>Nota 3</th>
        <th>Promedio</th>
    </tr>
    <?php foreach($_SESSION["registro"] as $estudiante): ?>
        <?php 
        $notas_estudiantes = $estudiante["notas"];
        $promedio_notas = array_sum($notas_estudiantes)/ count($notas_estudiantes);
        ?>

        <tr>
            <td><?= $estudiante["nombre"] ?></td>
            <td><?= $estudiante["carnet"] ?></td>
            <td><?= $estudiante["notas"][0] ?></td>
            <td><?= $estudiante["notas"][1]?></td>
            <td><?= $estudiante["notas"][2]?></td>
            <td><?= number_format($promedio_notas, 2) ?></td>
        </tr>
    <?php endforeach; ?>
</Table>

</div>


    
</body>
</html>