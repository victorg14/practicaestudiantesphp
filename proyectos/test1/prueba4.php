<?php 

$listaAlumnos =[
    "TPI"=>[
        ["Nombre"=>"Dayna", "carnet"=>"MS21017"], 
        ["Nombre"=>"Jairo", "carnet"=>"AA23027"]],
    "SO"=>["Vilma 1", "Vilma 2"]
];
?>


<h1>Listado de estudiantes por materia</h1>

<?php   

foreach($listaAlumnos as $clave => $itemList){
    echo "<h2>$clave</h2>";
    foreach($itemList as $claveItem=>$valotItem){
        ?>
    <p><?=  $valotItem["Nombre"] ?></p>
    <?php
    }
}

?>