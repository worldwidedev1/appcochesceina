<?php
include "./templates/header.php";
include "./classes/class.db.php";
$mostrarCompradores = new DBforms();
// Muestro la vista en HTML
?>
<div class="caja-contenedora">
    <div class="caja-seleccion">
        <a class="button" href="./index.php">Menú</a>
    </div>
    <h3>
        Mostrar compradores
    </h3>
    <hr>

    <?php 
    $listaCompradores = $mostrarCompradores->obtenerCompradores();
    //echo "listaCompradores: ";
    //var_dump($listaCompradores);

    foreach ($listaCompradores as $clave => $valor) {
        //echo "valor: ";
        //var_dump($valor);
        foreach ($valor as $key => $value) {
            echo $value . " ";
        }
        echo '<br />';
    };
    ?>
</div>
