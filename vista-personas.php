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
    $listaPersonas = $mostrarPersonas->obtenerPersonas();
    //echo "listaPersonas: ";
    //var_dump($listaPersonas);

    foreach ($listaPersonas as $clave => $valor) {
        //echo "valor: ";
        //var_dump($valor);
        foreach ($valor as $key => $value) {
            echo $value . " ";
        }
        echo '<br />';
    };
    ?>
</div>
