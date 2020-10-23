<?php
include "./templates/header.php";
include "./classes/class.forms.php";
include "./classes/class.db.php";

$formularioIntroducir = new Formulario();
$enviarCoche = new DBforms();

// Compruebo si estamos en método POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    //echo "Post: <pre>"; /*** */
    //print_r($_POST); /*** */
    //echo "</pre>"; /*** */
    $formularioIntroducir->enviarFormulario($_POST);
}

// Compruebo si la clase existe y estamos en método POST
$existeValidacion = !empty($formularioIntroducir) && $_SERVER["REQUEST_METHOD"] === "POST" ? true : false;

// Muestro el formulario en HTML
?>

<div class="caja-contenedora">
    <div class="caja-seleccion">
        <a class="button" href="./index.php" >Menú</a>
    </div>
    <h3>
        Introducir coche
    </h3>

    <hr> 
    
    <form
        action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
        method="post"
    >

        <?php
            /* // Se recibe un array multiple al cual se accede para coger los datos requeridos 
            // y pasarlos a un array simple, que podrá ser tratado correctamente en showInput.
            $arrayMultiple = $enviarCoche->obtenerVendedores();
            $arrayUnico = array();

            foreach ($arrayMultiple as $key => $value) {
                //echo $value["dni"] . "<br>";
                $arrayUnico[$key] = $value["dni"];
            } */

            $guardarVendedor = "idVendedor"; // nombre de la llave del valor que se quiere almacenar
            $mostrarVendedor = "dni"; // nombre de la llave del valor que se quiere mostrar en HTML

            $formularioIntroducir->showInput(
                $type = "select",
                $id = "idVendedor",
                $name = "idVendedor",
                $placeholder = "Vendedor",
                $label = "Vendedor:",
                $validacion = $existeValidacion,
                $options = $formularioIntroducir->arrayBidiMono($enviarCoche->obtenerVendedores(), $guardarVendedor, $mostrarVendedor), //$arrayUnico,
                $multiple = false
            );
            $formularioIntroducir->showInput(
                $type = "text",
                $id = "marca",
                $name = "marca",
                $placeholder = "Marca",
                $label = "Marca:",
                $validacion = $existeValidacion
            );
            $formularioIntroducir->showInput(
                $type = "text",
                $id = "modelo",
                $name = "modelo",
                $placeholder = "Modelo",
                $label = "Modelo:",
                $validacion = $existeValidacion
            );
            $formularioIntroducir->showInput(
                $type = "text",
                $id = "combustible",
                $name = "combustible",
                $placeholder = "Combustible",
                $label = "Combustible:",
                $validacion = $existeValidacion
            );
            $formularioIntroducir->showInput(
                $type = "text",
                $id = "color",
                $name = "color",
                $placeholder = "Color",
                $label = "Color:",
                $validacion = $existeValidacion
            );
            $formularioIntroducir->showInput(
                $type = "text",
                $id = "precio",
                $name = "precio",
                $placeholder = "Precio",
                $label = "Precio:",
                $validacion = $existeValidacion
            );
        ?>

        <button type="submit" class="submit">Enviar coche</button>
    </form>
</div>

<?php
// Compruebo si hay errores
$errores = $formularioIntroducir->hayErrores();

echo "<pre>";
print_r($formularioIntroducir->datosRecibidos);
echo "</pre>";

// Si no hay errores y se ha validado (la clase existe y estamos en método POST)
if (!$errores && $existeValidacion) {
    // se envian las variables a la base de datos junto con una cadena de caracteres, en la que se 
    // indica el tipo correspondiente de éstas y que debe coincidir con los parámetros de la sentencia
    $idCoche = $enviarCoche->enviarCoche(
        'issssi',
        $formularioIntroducir->datosRecibidos['idVendedor'],
        $formularioIntroducir->datosRecibidos['marca'],
        $formularioIntroducir->datosRecibidos['modelo'],
        $formularioIntroducir->datosRecibidos['combustible'],
        $formularioIntroducir->datosRecibidos['color'],
        $formularioIntroducir->datosRecibidos['precio']
    );

    if (!empty($idCoche)) {
        echo "<br>";
        echo "<p class='valid-input' id='guardado'>Se ha recibido y guardado correctamente los datos introducidos de coche</p>";
    }
}

if (count($errores) > 0) {
    echo "<p>El formulario coche contiene errores y no se ha enviado</p>";
    echo "Errores contados: ".count($errores);
}
?>

<?php include "./templates/footer.php";?>