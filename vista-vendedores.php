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

    <table>
    
    <?php 
    $listaVendedores = $mostrarVendedores->obtenerVendedores();
    //echo "listaVendedores: ";
    //var_dump($listaVendedores);

    if(!empty($listaVendedores)){
        // Recogemos los títulos de los campos para mostrarlos
        $campos = array_keys($listaVendedores[0]);
        //var_dump($titulos);
        echo "<thead><tr> ";
        foreach ($titulos as $titulo) {
            echo "<th><b>" . strtoupper($titulo) . "</b></th>";
        }
        echo "</tr></thead><tbody>";

        //echo '<br />';

        // Mostramos los datos de los registros en cada campo
        foreach ($listaVendedores as $clave => $registro) {
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
