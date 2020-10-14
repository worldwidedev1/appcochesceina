<?php

include "./templates/header.php";
include "./classes/class.db.php";

$mostrarPersonas = new DBforms();

const OBJETO = "personas";
const MENU = "index.php";

// Muestro la vista en HTML
?>

<div class="caja-contenedora">
    <div class="caja-seleccion">
        <a class="button" href="./"<?php MENU ?> >Menú</a>
    </div>
    <h3>
        Mostrar <?php echo OBJETO ?>
    </h3>

    <hr> 
    
    <?php 
    //$listaPersonas = array();
    $mostrarPersonas->obtenerPersonas();
    /*
    while ($valores = mysqli_fetch_array($listaPersonas)) {
        echo "<p>";
        // En esta sección estamos llenando el select con datos extraidos de una base de datos.
        echo "<pre>" . $valores['idPersona'] . ' ' . $valores['nombre'] . ' ' .$valores['apellidos'] . ' ' . $valores['dni'] . "</pre>";
    }
    echo "</p>";*/
    ?>
</div>
