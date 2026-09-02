<?php

session_start();

$_SESSION["registro"] ??= [];

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $nombre = trim($_POST["nombre_trabajador"]);
    $sueldo = trim($_POST["sueldo_trabajador"]);

    $_SESSION["registro"][]=[
        "nombre" => $nombre,
        "sueldo" => $sueldo
    ];

    header("Location:" . $_SERVER['PHP_SELF']);
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

<div>
    <h1>Ingresar Trababajores</h1>

    <form action="" method="post">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre_trabajador" required>

        <label for="Sueldo">Sueldo</label>
        <input type="text" id="sueldo" name="sueldo_trabajador" required>



        <button class="btn_guardar_datos">Guardar Datos</button>

    </form>

</div>
<div>
    <h1>Lista de trabajadores</h1>
    <?php foreach($_SESSION["registro"] as $trabajador): ?>

        <p><?= $trabajador["nombre"] ?></p>
        <p><?= $trabajador["sueldo"] ?></p>

    <?php endforeach; ?>

</div>
    
</body>
</html>