<?php
    require_once(__DIR__."/classes/autoload.php");
    $servidor    = "localhost";
    $basededatos = "ventasweb";
    $usuario     = "root";
    $clave       = "";

    $terminal = new MariaDB($servidor, "3306", $basededatos, $usuario, $clave);

    $empleados = $terminal->ejecutar("SELECT * FROM usuario");
    $productos = $terminal->ejecutar ("SELECT * FROM producto");

    $id_venta = "";
    $nombre_vendedor = "";
    $venta_detalle = [];
    if(isset($_POST['id_venta'])){
        $id_usuario = isset($_POST['usuario']) ? $_POST['usuario'] : $_POST['id_usuario']; 
        if(empty($_POST['id_venta'])){                      
            $fecha = date("Y-m-d H:i:s");
            $venta = $terminal->ejecutar("INSERT INTO venta (fecha, total_venta, id_usuario) VALUES ('$fecha', 0, $id_usuario)");
            $id_venta = $venta[0]['ID'];
        } else {
           $id_venta    = $_POST['id_venta'];
           $id_producto = $_POST['producto'];
           $cantidad    = $_POST['cantidad'];
           $precio    = $terminal->ejecutar("SELECT * from precio WHERE id_producto = $id_producto");
           $unitario  = $precio[0]['monto'];

           $detalle = $terminal->ejecutar("INSERT INTO venta_detalle (id_venta, id_producto, cantidad, unitario) VALUES ($id_venta, $id_producto, $cantidad, $unitario)");
           $venta_detalle = $terminal->ejecutar("SELECT vt.cantidad, p.nombre as nombre_producto, vt.unitario from venta_detalle vt INNER JOIN producto p ON vt.id_producto = p.id_producto WHERE id_venta = $id_venta");
        }
        $vendedor = $terminal->ejecutar("SELECT * FROM usuario WHERE id_usuario = ".$id_usuario);
        $nombre_vendedor = $vendedor[0]['apodo'];   
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminal de Punto de Venta :: Panel</title>
    <!-- Hojas de Estilos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
    <style>
    body {
        background-color: indigo;
    }
    .container{
        background-color: white;
    }
    .label-empty{
        line-height: 18px;
        width: 100%;
    }
    
    </style>
</head>
<body>
    <!-- Construimos el panel de ventas -->
    <form action="index.php" method="POST">
        <input type="hidden" name="id_venta" value="<?php echo $id_venta; ?>">
        <input type="hidden" name="total_venta">
        <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <hr>
                </div>
            </div>
            <?php if(!isset($_POST['id_venta'])){ ?>
            <div class="row">
                <div class="col-10">
                    <label class="control-label" for="usuario">Nombre del usuario:</label>
                    <select name="usuario" id="usuario" class="form-control">
                        <option value="" selected="true">-- Seleccione</option>
                        <?php
                            foreach($empleados as $empleado){
                                echo "<option value='{$empleado['id_usuario']}'>{$empleado['apodo']}</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="col-2">
                <label class="control-label label-empty" for="seleccionar"></label>
                    <button class="btn btn-warning" id="seleccionar" name="seleccionar">Seleccionar</button>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <hr>
                </div>
            </div>
            <?php } else { ?>
            <div class="row">
                <div class="col-6">
                    <label class="control-label" for="vendedor">Nombre del vendedor:</label>
                    <input type="text" name="vendedor" id="vendedor" class="form-control" readonly="true" value="<?php echo $nombre_vendedor; ?>">
                </div>
                <div class="col-6">
                    <label class="control-label" for="fecha">Fecha:</label>
                    <input type="text" name="fecha" id="fecha" class="form-control" readonly="true" value="<?php echo date("d-m-Y");?>">  
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <label class="control-label" for="producto">Nombre del producto:</label>
                    <select name="producto" id="producto" class="form-control">
                        <option value="" selected="true">-- Seleccione</option>
                        <?php
                            foreach($productos as $producto){
                                echo "<option value='{$producto['id_producto']}'>{$producto['nombre']}</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="col-3">
                    <label class="control-label" for="cantidad">Cantidad:</label>
                    <input type="number" name="cantidad" id="cantidad" class="form-control text-right" value="0">
                </div>
                <div class="col-3">
                    <label class="control-label label-empty" for="agregar"></label>
                    <button class="btn btn-success"><i class="fas fa-plus"></i> Agregar</button>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <hr>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Cant.</th>
                                    <th>Producto</th>
                                    <th>$ Unit.</th>
                                    <th>$ Total</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($venta_detalle as $fila){
                                    $total = $fila['cantidad'] * $fila['unitario'];
                                    echo "<tr><td>{$fila['cantidad']}</td><td>{$fila['nombre_producto']}</td><td>{$fila['unitario']}</td><td>$total</td><td></td></tr>";
                                }
                                ?>
                            </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <hr>
                </div>
            </div>
            <?php } ?>
        </div>
    </form>
</body>
</html>