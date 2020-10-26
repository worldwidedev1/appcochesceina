<?php
include "./templates/header.php";
include "./classes/class.forms.php";
include "./classes/class.db.php";

// Crear instancias o objetos para 
$formularioIntroducir = new Formulario(); // la introducción de datos en formulario
$enviarComprador = new DBforms(); // el envio de datos del formulario a la BD

// Compruebo si estamos en método POST para obtener booleano de post
$metodoPost = $_SERVER["REQUEST_METHOD"] === "POST";

if ($metodoPost) {
    //echo "<pre>Estic en post i envio les dades del formulari</pre>"; /*** */

    // Si estamos en el método POST, se envian los datos introducidos en el formulario 
    // método:  enviarFormulario 
    // clase:   Formulario (class.forms.php) 
    // objeto:  $formularioIntroducir 
    $formularioIntroducir->enviarFormulario($_POST);
}

// Compruebo si el objeto existe y estamos en método POST para obtener booleano de validación
// ATENCIÓN: el formulario siempre estará lleno, puesto que al haber un array que contiene un 
// elemento, aunque éste esté vacio, lo cuenta. Se tendria que mirar dentro del array elemento 
// por elemento.
$existeValidacion = !empty($formularioIntroducir) && $metodoPost ? true : false;
//echo "<pre>Existeix validació?: "; /*** */
//print_r($existeValidacion); /*** */
//echo "</pre><pre>Contingut del formulari: "; /*** */
//print_r($formularioIntroducir); /*** */
//echo "</pre>"; /*** */

include "./templates/body.php";
// Muestro el formulario en HTML
?>

<div class="caja-contenedora">
    <h3>
        Introducir comprador
    </h3>

    <hr> 
    
    <form
        action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
        method="post"
    >

        <?php
            // Envia los campos al método de la clase Formulario para sanitizarlos y validarlos. En HTML, 
            // asigna un nombre a la clase y muestra un mensaje de información sobre los datos introducidos.
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

        <button type="submit" class="submit">Enviar comprador</button>
    </form>
</div>

<?php
// Compruebo si hay errores
// método:  hayErrores 
// clase:   Formulario (class.forms.php) 
// objeto:  $formularioIntroducir
$errores = $formularioIntroducir->hayErrores();
//echo "Visualizar errores: ".$errores; /*** */

// Si no hay errores y se ha validado (la clase existe y estamos en método POST)
if (!$errores && $existeValidacion) {
    // se envian los campos a la base de datos junto con una cadena de caracteres, en la que cada carácter 
    // indica el tipo correspondiente del campo y que debe coincidir con los parámetros de la sentencia.
    // Se obtiene el idPersona a partir de enviar los campos al método enviarPersona del objeto enviarComprador de la clase DBforms
    $idPersona = $enviarComprador->enviarPersona(
        'sss',
        $formularioIntroducir->datosRecibidos['nombre'],
        $formularioIntroducir->datosRecibidos['apellidos'],
        $formularioIntroducir->datosRecibidos['dni']
    );

    $idComprador = $enviarComprador->enviarComprador(
        'i',
        $idPersona
    );

    if (!empty($idComprador)) {
        echo "<p class='valid-input' id='guardado'>Se ha recibido y guardado correctamente los datos introducidos de comprador</p>";
    }
}

if (count($errores) > 0) {
    echo "<br><p>El formulario comprador contiene errores y no se ha enviado.</p>";
    echo "<p>Errores contados: ".count($errores)."</p>";
}
?>

<?php include "./templates/footer.php";?>