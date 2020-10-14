<?php

include "./templates/header.php";
include "./classes/class.db.php";

$mostrarCompradores = new DBforms();

const OBJETO = "compradores";
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
    $listaCompradores = $mostrarCompradores->obtenerCompradores();
    echo "<p>";
    while ($valores = mysqli_fetch_array($query)) {
        // En esta sección estamos llenando el select con datos extraidos de una base de datos.
        echo $valores['idComprador'].' '.$valores['idPersona']; // .' '.$valores['nombre'].' '.$valores['apellidos'].' '.$valores['dni'];
    }
    echo "</p>";
    ?>
</div>
