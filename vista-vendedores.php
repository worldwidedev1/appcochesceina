<?php
include "./templates/header.php";
include "./classes/class.db.php";
$mostrarVendedores = new DBforms();
// Muestro la vista en HTML
?>
<div class="caja-contenedora">
    <div class="caja-seleccion">
        <a class="button" href="./index.php">Menú</a>
    </div>
    <h3>
        Mostrar vendedores
    </h3>
    <hr>

    <?php 
    $listaVendedores = $mostrarVendedores->obtenerVendedores();
    //echo "listaVendedores: ";
    //var_dump($listaVendedores);

    foreach ($listaVendedores as $clave => $valor) {
        //echo "valor: ";
        //var_dump($valor);
        foreach ($valor as $key => $value) {
            echo $value . " ";
        }
        echo '<br />';
    };
    ?>
</div>
