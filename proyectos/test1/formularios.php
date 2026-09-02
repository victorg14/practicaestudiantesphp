<?php 
session_start();

$_SESSION["registro"] ??= [];

if($_SERVER["REQUEST_METHOD"]==="POST"){
 echo "Datos post";  
 
 $nombreEstudiante = $_POST["nombre_estudiante"];
 $notas = $_POST["notas"];

 $_SESSION['registro'][]=[
    "nombre"=>$_POST["nombre_estudiante"],
    "carnet"=>$_POST["carnet"],
    "notas"=>$_POST["notas"]
 ];

 echo $nombreEstudiante;
 var_dump($_SESSION['registro']);
 
}
else{
    echo "Datos por GET";
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
    <form action="" method="POST">
        <label for="">Nombre</label>
        <input type="text" name="nombre_estudiante">
        <label for="">Carnet</label>
        <input type="text" name="carnet_estudiante">
        <label for="">Nota 1 </label>
        <input type="text" name="notas[]">
        <label for="">Nota 2 </label>
        <input type="text" name="notas[]">
        <label for="">Nota 3 </label>
        <input type="text" name="notas[]">
        <button type="submit">Enviar</button>
    </form>
</div>
    
</body>
</html>