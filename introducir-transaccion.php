<?php
include "./templates/header.php";
include "./classes/class.db.php";
include "./classes/class.forms.php";

$formularioIntroducir = new Formulario();
$enviarTransaccion = new DBforms();

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $formularioIntroducir->enviarFormulario($_POST);
}

$existeValidacion = !empty($formularioIntroducir) && $_SERVER["REQUEST_METHOD"] === "POST" ? true : false;

include "./templates/body.php";
?>

<div class="caja-contenedora">
    <h3>
        Introducir transacción
    </h3>
    
    <hr> 
    
    <form
        action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
        method="post"
    >

        <?php
            $guardarVendedor = "idVendedor";
            $mostrarVendedor = "dni";

            $guardarComprador = "idComprador";
            $mostrarComprador = "dni";

            $guardarCoche = "idCoche";
            $mostrarCoche = "matricula";

            $formularioIntroducir->showInput(
                $type = "select",
                $id = "idVendedor",
                $name = "idVendedor",
                $placeholder = "Vendedor",
                $label = "Vendedor:",
                $validacion = $existeValidacion, 
                $options = $formularioIntroducir->arrayBidiMono($enviarTransaccion->obtenerVendedores(), $guardarVendedor, $mostrarVendedor), //$arrayUnico,
                $multiple = false
            );
            $formularioIntroducir->showInput(
                $type = "select",
                $id = "idComprador",
                $name = "idComprador",
                $placeholder = "Comprador",
                $label = "Comprador:",
                $validacion = $existeValidacion, 
                $options = $formularioIntroducir->arrayBidiMono($enviarTransaccion->obtenerCompradores(), $guardarComprador, $mostrarComprador), //$arrayUnico,
                $multiple = false
            );
            $formularioIntroducir->showInput(
                $type = "select",
                $id = "idCoche",
                $name = "idCoche",
                $placeholder = "Matricula",
                $label = "Matricula:",
                $validacion = $existeValidacion, 
                $options = $formularioIntroducir->arrayBidiMono($enviarTransaccion->obtenerCoches(), $guardarCoche, $mostrarCoche), //$arrayUnico,
                $multiple = false
            );
            $formularioIntroducir->showInput(
                $type = "text",
                $id = "created_at",
                $name = "created_at",
                $placeholder = "Fecha transacción (año/mes/dia)",
                $label = "Fecha transacción (año/mes/dia):",
                $validacion = $existeValidacion
            );
        ?>

        <button type="submit" class="submit">Enviar transacción</button>
    </form>
</div>

<?php
// Compruebo si hay errores
    $errores = $formularioIntroducir->hayErrores();
    //echo "Visualizar errores: ".$errores; /*** */

// Si no hay errores y se ha validado (la clase existe y estamos en método POST)
if (!$errores && $existeValidacion) {
    // se envian las variables a la base de datos junto con una cadena de caracteres, en la que se 
    // indica el tipo correspondiente de éstas y que debe coincidir con los parámetros de la sentencia
    $idTransaccion = $enviarTransaccion->enviarTransaccion(
        'iis',
        $formularioIntroducir->datosRecibidos['idVendedor'],
        $formularioIntroducir->datosRecibidos['idComprador'],
        $formularioIntroducir->datosRecibidos['created_at']
    );

    $idTransaccionCoche = $enviarTransaccion->enviarTransaccionCoche(
        'ii',
        $idTransaccion,
        $formularioIntroducir->datosRecibidos['idCoche']
    );

    if (!empty($idTransaccionCoche)) {
        echo "<p class='valid-input' id='guardado'>Se ha recibido y guardado correctamente los datos introducidos de transacción</p>";
    }
}

if (count($errores) > 0) {
    echo "<br><p>El formulario transacción contiene errores y no se ha enviado.</p>";
    echo "<p>Errores contados: ".count($errores)."</p>";
}
?>

<?php include "./templates/footer.php";?>

