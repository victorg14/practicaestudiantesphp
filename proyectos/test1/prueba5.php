<?php

$cadena_prueba = "Hola mundo";

echo "<br><hr>";
echo "Longitud de la cadena ", strlen($cadena_prueba);


echo "<br><hr>";
echo "Comparacion si existe la palabra hola ", str_contains($cadena_prueba, "hola")? "Si se encuentra" : "No se encuentra";
echo "<br><hr>";
echo "Numero de palabras ", str_word_count($cadena_prueba);

echo "<br><hr>";
echo "TEXTO EN MAYUSCULAS ", strtoupper($cadena_prueba);
echo "<br><hr>";
echo $cadena_prueba;

echo "Texto invertido ", strrev($cadena_prueba);
echo "<br><hr>";

$vectorCadena = explode(" ", $cadena_prueba);
echo "<br><hr>";
var_dump($vectorCadena);

echo $vectorCadena[0];

$arreglo_letras = ["A", "B", "C"];
echo "<br><hr>";
echo "arreglo convertido", implode(", ", $arreglo_letras);
echo "<br><hr>";
var_dump(implode(", ", $arreglo_letras));
echo "<br><hr>";
var_dump($arreglo_letras);

$arregloNumeros = array(1,2,3,4);

$arregloNumeros[]=100;

array_push($arregloNumeros,101,102,103);
echo "<br><hr>";
var_dump($arregloNumeros);

$arreglo_filtrado = array_filter($arregloNumeros, fn($value) => $value % 2 ==0);
echo "<br><hr>";
print_r($arreglo_filtrado);

$arreglo_map = array_map(function ($item) {return strtolower($item);},$arreglo_letras);
echo "<br><hr>";
print_r($arreglo_map);


$union_arreglos = array_merge($arreglo_letras, $arregloNumeros);
echo "<br><hr>";
print_r($union_arreglos);
?>



<h1>Numeros </h1>
<ul>
    <?php  
    
    foreach($arregloNumeros as $item){
        ?>
        <li>
            <p style="display: block; background: brown; color:white;"><?=  $item ?></p>
        </li>
    <?php 
    }
    ?>
</ul>

<p>numero de elementos en el arreglo: <?= count($arregloNumeros);  ?></p>
<p>numero de elementos en el arreglo: <?= array_sum($arregloNumeros);  ?></p>