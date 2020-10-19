<?php

include "./templates/header.php";
include "./classes/class.forms.php";
include "./classes/class.db.php";

$formularioIntroducir = new Formulario();
$enviarCoche = new DBforms();

const OBJETO = "coche";
const MENU = "index.php";

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
        <a class="button" href="./"<?php MENU ?> >Menú</a>
    </div>
    <h3>
        Introducir <?php echo OBJETO ?>
    </h3>

    <hr> 
    
    <form
        action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
        method="post"
    >

        <label for="usuario">¿Qué usuario eres?
        </label>
        <select id="usuario" name="usuario" >
            <option value="" selected disabled>--Por favor, escoge una opción--</option>

            <?php foreach ($formularioIntroducir->array_personas_validas as $key => $value) : ?>
                    <option value="<?php echo $key; ?>"><?php echo $value ?></option>
            <?php endforeach;

            $usuario = $_POST["usuario"]; 

            if ($usuario === "comprador") {
                $formularioIntroducir->showInput(
                $type = "number",
                $idComprador = "idComprador",
                $name = "idComprador",
                $placeholder = "Número del Comprador",
                $label = "Número del Comprador:",
                $validacion = $existeValidacion
            );
            $idVendedor = null;
            } elseif ($usuario === "vendedor") {
                $formularioIntroducir->showInput(
                $type = "number",
                $idVendedor = "idVendedor",
                $name = "idVendedor",
                $placeholder = "Número del Vendedor",
                $label = "Número del Vendedor:",
                $validacion = $existeValidacion
            );
            $idComprador = null;
            } else {
                $placeholder = "Debes seleccionar un usuario antes";
            }
            
            ?>

        </select>

        <?php
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

        <button type="submit" class="submit">Enviar <?php echo OBJETO ?></button>
    </form>
</div>

<?php
// Compruebo si hay errores
$errores = $formularioIntroducir->hayErrores();

// Si no hay errores y se ha validado (la clase existe y estamos en método POST)
if (!$errores && $existeValidacion) {
    // se envian las variables a la base de datos junto con una cadena de caracteres, en la que se 
    // indica el tipo correspondiente de éstas y que debe coincidir con los parámetros de la sentencia
    $idCoche = $enviarCoche->enviarCoche(
        'iissssi',
        $formularioIntroducir->datosRecibidos['idVendedor'],
        $formularioIntroducir->datosRecibidos['idComprador'],
        $formularioIntroducir->datosRecibidos['marca'],
        $formularioIntroducir->datosRecibidos['modelo'],
        $formularioIntroducir->datosRecibidos['combustible'],
        $formularioIntroducir->datosRecibidos['color'],
        $formularioIntroducir->datosRecibidos['precio']
    );

    if (!empty($idCoche)) {
        echo "<br>";
        echo "<p class='valid-input' id='guardado'>Se ha recibido y guardado correctamente los datos introducidos de " . OBJETO . "</p>";
    }
}

if (count($errores) > 0) {
    echo "<p>El formulario ". OBJETO." contiene errores y no se ha enviado</p>";
    echo "Errores contados: ".count($errores);
}
?>

<?php include "./templates/footer.php";?>