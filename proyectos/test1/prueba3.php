<?php 

$nombre_alumno= "Hector";
$edad_alumno = 6;

echo "Hola, mundo";

print "hola mundo";
echo "<br><hr>";
$arreglo = ["item 1", "item 2"];
echo "<br><hr>";
print_r($arreglo);
echo "<br><hr>";
var_dump($arreglo);
echo "<br><hr>";
var_dump($nombre_alumno);
echo "<br><hr>";
$precio_matricula = 58.4567837465;
echo "<br><hr>";
echo $precio_matricula;
echo "<br><hr>";
printf("Valor de matricula %.2f", $precio_matricula);
echo "<br><hr>";
echo "\" $$precio_matricula\"";

/*echo <<<HTML 
        <p> texto: $nombre_alumno</p> 
        HTML;*/
?>


<h1><?= $nombre_alumno  ?></h1>