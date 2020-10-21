<?php
include "./templates/header.php";
include "./classes/class.db.php";

$mostrarPersonas = new DBforms();

// Muestro la vista en HTML
?>

<div class="caja-contenedora">
    <div class="caja-seleccion">
        <a class="button" href="./index.php">Menú</a>
    </div>
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
        $campos = array_keys($listaPersonas[0]);
        //var_dump($campos);
        echo "<thead><tr> ";
        for($i=0; $i < count($campos); $i++){
            echo "<th><b>" . $campos[$i] . "</b></th>";
        }
        echo "</tr></thead><tbody>";

        //echo '<br />';

        // Mostramos los datos de los registros en cada campo
        foreach ($listaPersonas as $clave => $valor) {
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
