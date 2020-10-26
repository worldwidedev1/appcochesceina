<?php
include "./templates/header.php";
include "./classes/class.forms.php";
include "./classes/class.db.php";

$formularioIntroducir = new Formulario();
$enviarVendedor = new DBforms();

// Compruebo si estamos en método POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    //echo "Post: <pre>"; /*** */
    //print_r($_POST); /*** */
    //echo "</pre>"; /*** */
    $formularioIntroducir->enviarFormulario($_POST);
}

// Compruebo si la clase existe y estamos en método POST
$existeValidacion = !empty($formularioIntroducir) && $_SERVER["REQUEST_METHOD"] === "POST" ? true : false;

include "./templates/body.php";
// Muestro el formulario en HTML
?>

<div class="caja-contenedora">
    <h3>
        Introducir vendedor
    </h3>
    
    <hr> 
    
    <form
        action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
        method="post"
    >

        <?php
            $formularioIntroducir->showInput(
                $type = "text",
                $id = "nombre",
                $name = "nombre",
                $placeholder = "Nombre",
                $label = "Nombre:",
                $validacion = $existeValidacion
            );
            $formularioIntroducir->showInput(
                $type = "text",
                $id = "apellidos",
                $name = "apellidos",
                $placeholder = "Apellidos",
                $label = "Apellidos:",
                $validacion = $existeValidacion
            );
            $formularioIntroducir->showInput(
                $type = "text",
                $id = "dni",
                $name = "dni",
                $placeholder = "Dni",
                $label = "Dni:",
                $validacion = $existeValidacion
            );
        ?>

        <button type="submit" class="submit">Enviar vendedor</button>
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
    $idPersona = $enviarVendedor->enviarPersona(
        'sss',
        $formularioIntroducir->datosRecibidos['nombre'],
        $formularioIntroducir->datosRecibidos['apellidos'],
        $formularioIntroducir->datosRecibidos['dni']
    );

    $idVendedor = $enviarVendedor->enviarVendedor(
        'i',
        $idPersona
    );

    if (!empty($idVendedor)) {
        echo "<p class='valid-input' id='guardado'>Se ha recibido y guardado correctamente los datos introducidos de vendedor</p>";
    }
}

if (count($errores) > 0) {
    echo "<br><p>El formulario vendedor contiene errores y no se ha enviado.</p>";
    echo "<p>Errores contados: ".count($errores)."</p>";
}
?>

<?php include "./templates/footer.php";?>