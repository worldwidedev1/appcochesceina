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
        //var_dump($campos);
        echo "<thead><tr> ";
        for($i=0; $i < count($campos); $i++){
            echo "<th><b>" . $campos[$i] . "</b></th>";
        }
        echo "</tr></thead><tbody>";

        //echo '<br />';

        // Mostramos los datos de los registros en cada campo
        foreach ($listaVendedores as $clave => $valor) {
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
