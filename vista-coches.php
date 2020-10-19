<?php

include "./templates/header.php";
include "./classes/class.db.php";

$mostrarCoches = new DBforms();

const OBJETO = "coches";
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
    $listaCoches = $mostrarCoches->obtenerCoches();
    //echo "listaCoches: ";
    //var_dump($listaCoches);

    foreach ($listaCoches as $clave => $valor) {
        //echo "valor: ";
        //var_dump($valor);
        foreach ($valor as $key => $value) {
            echo $value . " ";
        }
        echo '<br />';
    };
    ?>
</div>
