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
        $campos = array_keys($listaCompradores[0]);
        //var_dump($campos);
        echo "<thead><tr> ";
        for($i=0; $i < count($campos); $i++){
            echo "<th><b>" . $campos[$i] . "</b></th>";
        }
        echo "</tr></thead><tbody>";

        //echo '<br />';

        // Mostramos los datos de los registros en cada campo
        foreach ($listaCompradores as $clave => $valor) {
            //echo "valor: ";
            //var_dump($valor);

            echo "<tr> ";
            foreach ($valor as $key => $value) {
                echo "<td>" . $value . "</td>";
            }
            echo "</tr>";
        };
        echo "</tbody>";
    }
    ?>
    
    </table>
</div>
