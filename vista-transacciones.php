<?php
include "./templates/header.php";
include "./classes/class.db.php";

$mostrarTransacciones = new DBforms();

include "./templates/body.php";
// Muestro la vista en HTML
?>

<div class="caja-contenedora">
    <h3>
        Mostrar transacciones
    </h3>

    <hr> 
    
    <table>
    
    <?php 
    // Recoge en un array los registros de cada coche, que se corresponden por cada uno de 
    // ellos a un array. Por tanto obtenemos un array bidimensional
    $listaTransacciones = $mostrarTransacciones->obtenerTransacciones();
    //echo "listaTransacciones: ";
    //var_dump($listaTransacciones);

    if(!empty($listaTransacciones)){
        // Recogemos las llaves de alguno de sus elementos o registros, por ejemplo el primero, 
        // ya que se corresponden con los títulos de los campos que se van a mostrar
        $titulos = array_keys($listaTransacciones[0]);
        //var_dump($titulos);

        // Mostramos los títulos de cada campo
        echo "<thead><tr> ";
        foreach ($titulos as $titulo) {
            echo "<th><b>" . strtoupper($titulo) . "</b></th>";
        }
        echo "</tr></thead><tbody>";

        // Mostramos por cada registro (fila)...
        foreach ($listaTransacciones as $clave => $registro) {
            //var_dump($registro);

            // ...los datos en cada campo (columna)
            echo "<tr> ";
            foreach ($registro as $key => $campo) {
                /*echo print_r($key);
                echo "<br>";*/
                //$resultados = print_r($key, true); // $resultados contiene ahora la salida de print_r
                if($key == "fecha"){
                    $campo = date("d/m/Y", strtotime($campo)); // convierte fecha SQL a formato europeo (d-m-Y)
                }
                echo "<td>" . $campo . "</td>";
            }
            echo "</tr>";
        };
        echo "</tbody>";
    }
    ?>
    
    </table>
</div>
