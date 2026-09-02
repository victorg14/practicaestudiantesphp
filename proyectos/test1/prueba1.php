<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Lista de estudiantes</h1>
    <?php 

    $estudiantes = ["Victor", "Manuel", "Genesis"];
    
    ?>

    <table>

    <thead>
        <tr>Nombre</tr>
    </thead>
    
    <tbody>
        <?php 
        foreach($estudiantes as $estudiante): ?>
        <tr>
            <td><?= $estudiante ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>

    </table>
    
</body>
</html>