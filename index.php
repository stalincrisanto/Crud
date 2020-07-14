<?php
    /**ANTES QUE NADA DEBO TENER LA CONEXIÓN YA ABIERTA**/
    $conexion = mysqli_connect("127.0.0.1", "root", "", "tienda");
    if (!$conexion) 
    {
        echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
        echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
        echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }
    
    $codigo="";
    $nombre = "";
    $precio = "";
    $stock = "";
    $fechaEntrada = "";
    $descripcion = "";
    $accion = "Agregar";

    if(isset($_POST["accion"]) && ($_POST["accion"]=="Agregar"))
    {
        /**AQUI GUARDO EN LAS VARIABLES CON EL POST, PEEERO DEL POST TIENE QUE SER LO QUE MANDO DEL FORMULARIO**/
        $stmt = $conexion->prepare("INSERT INTO productos (nombre, precio, stock, fechaEntrada, descripcion) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sdiss", $nombre, $precio, $stock, $fechaEntrada, $descripcion);
        $nombre = $_POST["nombreProducto"];
        $precio = $_POST["precioProducto"];
        $stock = $_POST["stockProducto"];
        $fechaEntrada = $_POST["fechaProducto"];
        $descripcion = $_POST["descripcionProducto"];
        $stmt->execute();
        $stmt->close();
        //$codigo="";
        $nombre = "";
        $precio = "";
        $stock = "";
        $fechaEntrada = "";
        $descripcion = "";
    }

    else if(isset($_POST["accion"]) && ($_POST["accion"]=="Modificar"))
    {
        $stmt = $conexion->prepare("UPDATE productos set nombre=?,precio=?,stock=?,fechaEntrada=?,descripcion=? WHERE codigo=?");
        $stmt->bind_param("sdissi", $nombre, $precio, $stock, $fechaEntrada, $descripcion,$codigo);
        $nombre = $_POST["nombreProducto"];
        $precio = $_POST["precioProducto"];
        $stock = $_POST["stockProducto"];
        $fechaEntrada = $_POST["fechaProducto"];
        $descripcion = $_POST["descripcionProducto"];
        $codigo = $_POST["codigoProducto"];
        $stmt->execute();
        $stmt->close();
        $nombre = "";
        $precio = "";
        $stock = "";
        $fechaEntrada = "";
        $descripcion = "";
    }

    else if(isset($_GET["update"]))
    {
        $result = $conexion->query("SELECT * FROM productos WHERE codigo=".$_GET["update"]);
        if($result->num_rows>0)
        {
            $row1 = $result->fetch_assoc();
            $codigo = $row1["codigo"];
            $nombre = $row1 ["nombre"];
            $precio = $row1["precio"];
            $stock = $row1["stock"];
            $fechaEntrada = $row1["fechaEntrada"];
            $descripcion = $row1["descripcion"];
            $accion = "Modificar";
        }
    }

    else if(isset($_GET["delete"]))
    {
        $result = $conexion->query("SELECT * FROM productos WHERE codigo=".$_GET["delete"]);
        if($result->num_rows>0)
        {
            $row2 = $result->fetch_assoc();
            $codigoEliminar = $row2["codigo"];
        }
        $stmt = $conexion->prepare("DELETE FROM productos WHERE codigo=?");
        $stmt->bind_param("i", $codigo);
        $codigo = $codigoEliminar;
        $stmt->execute();
        $stmt->close();
        $codigo="";
    }

    /*else if(isset($_POST["eliminarCodigo"]))
    {
        $stmt = $conexion->prepare("DELETE FROM productos WHERE codigo=?");
        $stmt->bind_param("i", $codigo);
        $codigo = $_POST["eliminarCodigo"];
        $stmt->execute();
        $stmt->close();
        $codigo="";
    }*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet"  href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
    <script src="https://momentjs.com/downloads/moment.js"></script>
</head>
<body>
    <header style="background-color: #673AB7;">
        <h2 class="text-center text-light">PRODUCTOS</h2><br>
    </header><br><br>
    
    <!--INICIO TABLA-->
    <form action="index.php" name="forma" method="post" id="forma">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table id="tablaProductos" class="table table-striped table-bordered table-condensed" style="width: 100%;">
                        <thead class="text-center">
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Fecha de Entrada</th>
                                <th>Descripcion</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $result = $conexion->query("SELECT * FROM productos");
                    
                                if ($result->num_rows > 0) 
                                {
                                // output data of each row
                                    while($row = $result->fetch_assoc()) 
                                    { 
                            ?>
                            <tr>
                                <!--De aquí mando el update seteado para verificar arriba si es el modificar y eliminar OJO-->
                                <td><?php echo $row ["codigo"];?></td>
                                <td><?php echo $row ["nombre"];?></td>
                                <td><?php echo $row ["precio"];?></td>
                                <td><?php echo $row ["stock"];?></td>
                                <td><?php echo $row ["fechaEntrada"];?></td>
                                <td><?php echo $row ["descripcion"];?></td>
                                <td>
                                    <div class="text-center">
                                        <div class="btn-group">
                                            <a href="index.php?update=<?php echo $row ["codigo"];?>#editar" type="button" class="btn btn-primary">Editar</a>
                                            <a href="index.php?delete=<?php echo $row ["codigo"];?>" type="button" class="btn btn-danger">Eliminar</a>
                                            
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php
                                    }
                                }
                                else
                                {
                            ?>
                            <tr>
                                <td>No hay datos en la tabla</td>
                            </tr>
                            <?php
                                } 
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div><br>
    <!--FIN TABLA-->
    <div class="container">
        <div class="card">
            <div class="card-body"  style="background-color: #673AB7;">
            <h2 class="text-center text-light">Añadir Nuevo Producto</h2>
            </div>
        </div>
        <div>
            <div class="card-body">
                <!--<form action="index.php" name="forma" method="post" id="forma">-->
                    <input type="hidden" name="codigoProducto" value="<?php echo $codigo ?>">
                    <div class="form-group row" id="editar">
                        <label for="nombreProducto" id="lblNombreProducto" class="col-sm-2 col-form-label">Nombre</label>
                        <div class="col-sm-4">
                            <input type="text" name="nombreProducto" value="<?php echo $nombre ?>" require class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="precioProducto" id="lblPrecioProducto" class="col-sm-2 col-form-label">Precio</label>
                        <div class="col-sm-4">
                            <input type="text" name="precioProducto" value="<?php echo $precio?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="stockProducto" id="lblStockProducto" class="col-sm-2 col-form-label">Stock</label></td><br>
                        <div class="col-sm-4">
                        <input type="text" name="stockProducto" value="<?php echo $stock?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fechaProducto" id="lblFechaProducto" class="col-sm-2 col-form-label">Fecha Entrada</label>
                        <div class="col-sm-4">
                            <input type="date" name="fechaProducto" value="<?php echo $fechaEntrada ?>" class="form-control" onchange="obtenerFecha(this)">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="descripcionProducto" id="lblDescripcionProducto" class="col-sm-2 col-form-label">Descripción</label>
                        <div class="col-sm-4">
                            <input type="text" name="descripcionProducto" value="<?php echo $descripcion?>" class="form-control">
                        </div>
                    </div>
                    <input type="submit" name="accion" value="<?php echo $accion ?>" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</body>
<script>
    function eliminarProducto()
    {
        document.getElementById('forma').submit();
    }

    function obtenerFecha(e)
    {
        var fecha = moment(e.value);
        return fecha.format("YYYY/MM/DD")
    }
</script>
</html>