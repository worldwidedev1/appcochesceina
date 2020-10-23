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

    <table>
    
    <?php 
    $listaCompradores = $mostrarCompradores->obtenerCompradores();
    //echo "listaCompradores: ";
    //var_dump($listaCompradores);

    if(!empty($listaCompradores)){
        // Recogemos los títulos de los campos para mostrarlos
        $titulos = array_keys($listaCompradores[0]);
        //var_dump($titulos);

        // Mostramos los títulos de cada campo
        echo "<thead><tr> ";
        foreach ($titulos as $titulo) {
            echo "<th><b>" . strtoupper($titulo) . "</b></th>";
        }
        echo "</tr></thead><tbody>";

        //echo '<br />';

        // Mostramos los datos de los registros en cada campo
        foreach ($listaCompradores as $clave => $registro) {
            //echo "registro: ";
            //var_dump($registro);

            echo "<tr> ";
            foreach ($registro as $key => $campo) {
                echo "<td>" . $campo . "</td>";
            }
            echo "</tr>";
        };
        echo "</tbody>";
    }
    ?>
    
    </table>
</div>
