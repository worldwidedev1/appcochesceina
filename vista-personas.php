<?php
include "./templates/header.php";
include "./classes/class.db.php";

$mostrarPersonas = new DBforms();

include "./templates/body.php";
// Muestro la vista en HTML
?>

<div class="caja-contenedora">
    <h3>
        Mostrar personas
    </h3>

    <hr> 

    <table>

    <?php 
    $listaPersonas = $mostrarPersonas->obtenerPersonas();
    //echo "listaPersonas: ";
    //var_dump($listaPersonas);

    if(!empty($listaPersonas)){
        // Recogemos los títulos de los campos para mostrarlos
        $titulos = array_keys($listaPersonas[0]);
        //var_dump($titulos);
        echo "<thead><tr> ";
        foreach ($titulos as $titulo) {
            echo "<th><b>" . strtoupper($titulo) . "</b></th>";
        }
        echo "</tr></thead><tbody>";

        //echo '<br />';

        // Mostramos los datos de los registros en cada campo
        foreach ($listaPersonas as $clave => $registro) {
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
