<?php
require_once(__DIR__."/classes/autoload.php");

$servidor    = "localhost";
$basededatos = "ventasweb";
$usuario     = "root";
$clave       = "";

$mariaDB = new MariaDB($servidor, "3306", $basededatos ,$usuario, $clave);
$consulta = "SELECT * FROM producto";
$resultado = $mariaDB->ejecutar($consulta);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Hojas de Estilos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2>Lista de Productos del sistema</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre Producto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($resultado as $fila){
                                echo "<tr>";
                                echo "<td>".$fila['id_producto']."</td><td>".$fila['nombre']."</td>";                                
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>