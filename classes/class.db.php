<?php

/**
 * Clase que interactua con la BD.
 * 
 * Contiene la información de conexión y las sentencias para una gestión concreta de la BD.
 */
class DBforms {
    // Declara las propiedades (o variables, atributos, campos) de la clase
    public $servername;
    public $username;
    public $password;
    public $baseDatos;

    /**
     * Establece y asigna las propiedades del constructor de la clase.
     * 
     * @param string $servername alojamiento local del servidor (habitualmente localhost o 127.0.0.1)
     * @param string $username nombre de usuario con acceso al alojamiento local (habitualmente root)
     * @param string $password contrasenya del usuario que accede al alojamiento local
     * @param string $baseDatos nombre de la BD en la que se realiza la conexión
     */
    public function __construct(
        $servername = 'localhost',
        $username = 'root',
        $password = '',
        $baseDatos = 'app_coches'
    ) {
        // Dentro de cualquier método (invocado como objeto) de una clase se puede acceder a las propiedades: 
        // - no estáticas con la pseudovariable $this dentro y el operador de objeto -> 
        //   Ejemplo: $this->nombreDeLaPropiedad
        // - extáticas con la  el operador de objeto ::
        //   Ejemplo: self::$nombreDeLaPropiedad
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->baseDatos = $baseDatos;
    }

    /**
     * Crea la conexión a la BD.
     * 
     * Crea la conexión a la BD a partir de las propiedades generales de la clase utilizando el método 
     * mysqli de php, que tratará cada conexión como un objeto.
     * 
     * @return object
     */
    public function crearConexion()
    {
        return new mysqli(
            $this->servername,
            $this->username,
            $this->password,
            $this->baseDatos
        );
    }
    
    /**
     * Muestra un mensaje de error si no se establece la conexión correctamente.
     * 
     * @param object $miConexion
     */
    public function hayError($miConexion)
    {
        if ($miConexion->connect_error) {
            die("La conexión ha fallado. Error número: " . $miConexion->connect_errno . " Descripción error: " . $miConexion->connect_error);
        }
    }

    // COMPRADORES - INSERT
    // (Comentarios más detallados en éste método. Ejemplos para sentencias preparadas con mysqli pero no con PDO)
    /**
     * Inserta datos en la tabla Compradores de la BD.
     * 
     * @param string $datos
     * @param int $idPersona, 
     * 
     * @return int $id devuelve el id de la tabla Comprador
     */
    // idCompradores, idPersonas
    public function enviarComprador($datos, $idPersona)
    {
        //$this->showPre("DBASE-Entra enviarComprador datos: " . $datos); /*** */
        //$this->showPre("DBASE-enviarComprador idPersona: " . $idPersona); /*** */

        // Establezco la conexión
        $conexion = $this->crearConexion();

        // Creo una plantilla de la sentencia SQL, dejando sin especificar (?) los parámetros de los valores. 
        // La base de datos analiza, compila y realiza la optimización de la consulta sobre la sentencia SQL, 
        // y guarda el resultado sin ejecutarlo. Se reduce así: 
        // * el tiempo de análisis, ya que la preparación de la sentencia se realiza una única vez, mientras 
        //   que la ejecución se podrá realizar tantas veces como se quiera con valores diferentes.
        // * el ancho de banda consumido por el servidor, ya que sólo envia los parámetros y no la consulta entera.
        // * la posibilidad de inyecciones SQL, ya que los valores de los parámetros son transmitidos después 
        //   usando un protocolo diferente.
        $enviarComprador = $conexion->prepare("INSERT INTO Compradores(Personas_idPersona) VALUES (?);");

        // Se enlazan los valores con los parámetros (parametrización de sentencia). El argumento $datos 
        // especifica el tipo de datos para cada parámetro, que pueden ser de 4 tipos: 
        // * i  integer
        // * d  double
        // * s  string
        // * b  blob (tipo que permite contener gran cantidad de datos). El valor máximo lo especifica la 
        //      memória disponible y la medida del paquete de comunicación, que pueden ser establecidos.
        // Como alternativa, la opción bind_result permite enlazar las columnas con variables para su uso posterior 
        // sin hacer falta especificar el tipo de dato para cada parámetro. Mediante fetch se pueden obtener los valores.
        $enviarComprador->bind_param(
            $datos,
            $idPersona
        );

        // Compruebo si la conexión se establece bien
        if (!$enviarComprador) {
            throw new Exception($conexion->error_list);
            // hayError($conexion);
        }

        // Ejecuto la sentencia.
        $enviarComprador->execute();

        // Compruebo si se envia y no hay error
        if (!$enviarComprador) {
            throw new Exception($conexion->error_list);
        }

        // Devuelvo el último valor añadido en la última consulta.
        // La tabla debe tener columna de autoincremento y las declaraciones enviadas deben ser insert o update. 
        // Para cualquier otra opción la función insert_id devolverá cero.
        $id = $enviarComprador->insert_id;

        // Cierro conexión
        $enviarComprador->close();

        // Devuelvo el ID
        return $id;
    }

    // VENDEDORES - INSERT
    /**
     * Inserta datos en la tabla Vendedores de la BD.
     * 
     * @param string $datos
     * @param int $idPersona, 
     * 
     * @return int $id devuelve el id de la tabla Vendedor
     */
    // idVendedores, idPersonas
    public function enviarVendedor($datos, $idPersona)
    {
        $conexion = $this->crearConexion();
        $enviarVendedor = $conexion->prepare("INSERT INTO Vendedores(Personas_idPersona) VALUES (?);");
        $enviarVendedor->bind_param(
            $datos,
            $idPersona
        );

        // Compruebo si la conexión se establece bien
        if (!$enviarVendedor) {
            throw new Exception($conexion->error_list);
            // hayError($conexion);
        }

        // Ejecuto la query
        $enviarVendedor->execute();

        // Compruebo si se envia y no hay error
        if (!$enviarVendedor) {
            throw new Exception($conexion->error_list);
        }

        // Devuelvo el último valor añadido en la última consulta.
        // La tabla debe tener columna de autoincremento y las declaraciones enviadas deben ser insert o update. 
        // Para cualquier otra opción la función insert_id devolverá cero.
        $id = $enviarVendedor->insert_id;

        // Cierro conexión
        $enviarVendedor->close();

        // Devuelvo el ID
        return $id;
    }

    // PERSONAS - INSERT
    /**
     * Inserta datos en la tabla Personas de la BD.
     * 
     * @param string $datos
     * @param string $nombre, $apellidos, $dni 
     */
    // idPersona, nombre, apellidos, dni
    public function enviarPersona($datos, $nombre, $apellidos, $dni)
    {
        //$this->showPre("DBASE-Entra enviarPersona datos: " . $datos); /*** */

        $conexion = $this->crearConexion();
        //$this->showPre("DBASE-enviarPersona conexion: " . gettype($conexion)); /*** */
        //var_dump($conexion); /*** */
        $enviarPersona = $conexion->prepare("INSERT INTO Personas(
            nombre, 
            apellidos, 
            dni 
            ) VALUES (?, ?, ?);");
        //$this->showPre("DBASE-enviarPersona enviarPersona: " . gettype($enviarPersona)); /*** */
        //var_dump($enviarPersona); /*** */
        $enviarPersona->bind_param(
            $datos,
            $nombre, 
            $apellidos,
            $dni 
        );

        // Compruebo si la conexión se establece bien
        if (!$enviarPersona) {
            throw new Exception($conexion->error_list);
        }

        // Ejecuto la query
        $enviarPersona->execute();

        // Compruebo si se envia y no hay error
        if (!$enviarPersona) {
            throw new Exception($conexion->error_list);
        }

        // Devuelvo el último valor añadido en la última consulta.
        // La tabla debe tener columna de autoincremento y las declaraciones enviadas deben ser insert o update. 
        // Para cualquier otra opción la función insert_id devolverá cero.
        $id = $enviarPersona->insert_id;

        // Cierro conexión
        $enviarPersona->close();

        // Devuelvo el ID
        return $id;
    }

    // COCHES - INSERT
    /**
     * Inserta datos en la tabla Coches de la BD.
     * 
     * @param string $datos
     * @param int $idVendedor, $idComprador, 
     * @param string $marca, $modelo, $combustible, $color, 
     * @param int $precio
     */
    // idVendedor, idComprador, marca, modelo, combustible, color, precio
    public function enviarCoche($datos, $idVendedor=0, $idComprador=0, $marca, $modelo, $combustible, $color, $precio)
    {
        $conexion = $this->crearConexion();
        $enviarCoche = $conexion->prepare("INSERT INTO Coches(
            Vendedores_idVendedor, 
            Compradores_idComprador, 
            marca, 
            modelo, 
            combustible, 
            color, 
            precio
            ) VALUES (?, ?, ?, ?, ?, ?, ?);");
        $enviarCoche->bind_param(
            $datos,
            $idVendedor,
            $idComprador,
            $marca, 
            $modelo, 
            $combustible, 
            $color,
            $precio
        );

        // Compruebo si la conexión se establece bien
        if (!$enviarCoche) {
            throw new Exception($conexion->error_list);
        }

        // Ejecuto la query
        $enviarCoche->execute();

        // Compruebo si se envia y no hay error
        if (!$enviarCoche) {
            throw new Exception($conexion->error_list);
        }

        // Devuelvo el último valor añadido en la última consulta.
        // La tabla debe tener columna de autoincremento y las declaraciones enviadas deben ser insert o update. 
        // Para cualquier otra opción la función insert_id devolverá cero.
        $id = $enviarCoche->insert_id;

        // Cierro conexión
        $enviarCoche->close();

        // Devuelvo el ID
        return $id;
    }

    // TRANSACCIONES - INSERT
    /**
     * Inserta datos en la tabla Transacciones de la BD.
     * 
     * @param string $datos
     * @param int $idVendedor, $idComprador, 
     * @param date $createAt fecha de realización de la transacción
     */
    // idVendedores, idCompradores, createAt
    public function enviarTransaccion($datos, $idVendedor, $idComprador, $createAt)
    {
        $conexion = $this->crearConexion();
        $enviarTransaccion = $conexion->prepare("INSERT INTO Transaccion(
            Vendedores_idVendedor, 
            Compradores_idComprador, 
            createAt
            ) VALUES (?, ?, ?)");
        $enviarTransaccion->bind_param(
            $datos,
            $idVendedor,
            $idComprador,
            $createAt
        );

        // Compruebo si la conexión se establece bien
        if (!$enviarTransaccion) {
            throw new Exception($conexion->error_list);
        }

        // Ejecuto la query
        $enviarTransaccion->execute();

        // Compruebo si se envia y no hay error
        if (!$enviarTransaccion) {
            throw new Exception($conexion->error_list);
        }

        // Devuelvo el último valor añadido en la última consulta.
        // La tabla debe tener columna de autoincremento y las declaraciones enviadas deben ser insert o update. 
        // Para cualquier otra opción la función insert_id devolverá cero.
        $id = $enviarTransaccion->insert_id;

        // Cierro conexión
        $enviarTransaccion->close();

        // Devuelvo el ID
        return $id;
    }

    // TRANSACCIONES_has_COCHES - INSERT
    /**
     * 
     */
    // idTransacciones, idCoches
    /*public function enviarTransaccionCoche($datos, $idTransaccion, $idCoche)
    {
        $conexion = $this->crearConexion();
        $enviarTransaccionCoche = $conexion->prepare("INSERT INTO Transacciones_has_Coches(
            Transacciones_idTransaccion, 
            Coches_idCoche
            ) VALUES (?, ?)");
        $enviarTransaccionCoche->bind_param(
            $datos,
            $idTransaccion,
            $idCoche
        );

        // Compruebo si la conexión se establece bien
        if (!$enviarTransaccionCoche) {
            throw new Exception($conexion->error_list);
        }

        // Ejecuto la sentencia
        $enviarTransaccionCoche->execute();

        // Compruebo si se envia y no hay error
        if (!$enviarTransaccionCoche) {
            throw new Exception($conexion->error_list);
        }

        // Devuelvo el último valor añadido en la última consulta.
        // La tabla debe tener columna de autoincremento y las declaraciones enviadas deben ser insert o update. 
        // Para cualquier otra opción la función insert_id devolverá cero.
        $id = $enviarTransaccionCoche->insert_id;

        // Cierro conexión
        $enviarTransaccionCoche->close();
    }*/

    // MEDIA
    // path, mime_type, filesize
    /* De momento no implementado...
    public function enviarFile($datos, $file)
    {
        $conexion = $this->crearConexion();
        $enviarFile = $conexion->prepare("INSERT INTO MEDIA (path) VALUES (?)");
        $enviarFile->bind_param(
            $datos,
            $file
        );

        // Compruebo si la conexión se establece bien
        if (!$enviarFile) {
            throw new Exception($conexion->error_list);
        }

        // Ejecuto la query
        $enviarFile->execute();

        // Compruebo si se envia y no hay error
        if (!$enviarFile) {
            throw new Exception($conexion->error_list);
        }

        // Devuelvo el último valor añadido en la última consulta.
        // La tabla debe tener columna de autoincremento y las declaraciones enviadas deben ser insert o update. 
        // Para cualquier otra opción la función insert_id devolverá cero.
        $id = $enviarFile->insert_id;

        // Cierro conexión
        $enviarFile->close();
    }*/

    // COCHES - SELECT
    /**
     * Realiza una consulta en la tabla Coches
     * 
     * @return array $arrayCoches devuelve un array con los elementos que forman parte de la consulta
     */
    public function obtenerCoches()
    {
        // Establecer conexión
        $conexion = $this->crearConexion();

        // Prepara una plantilla de la sentencia SQL, detallando los campos por este motivo (en lugar de poner *)
        $prepare = $conexion->prepare("SELECT 
            idCoche, 
            Vendedores_idVendedor, 
            Compradores_idComprador, 
            marca, 
            modelo, 
            combustible, 
            color, 
            precio 
            FROM Coches");

        // Se envía la sentencia preparada a la BD, comprobando si hay algún error
        if (!$prepare) {
            //var_dump("Errores en sentencia coches: " . $conexion->error_list); /*** */
        }

        // Ejecuta la sentencia
        $prepare->execute();

        // Vincula variables a una sentencia preparada para el almacenamiento de resultados
        $prepare->bind_result(
            $idCoche, 
            $Vendedores_idVendedor, 
            $Compradores_idComprador, 
            $marca, 
            $modelo, 
            $combustible, 
            $color, 
            $precio
        );

        // Obtiene los resultados de una sentencia preparada en las variables vinculadas
        $miArray = array();
        while ($prepare->fetch()) {
            array_push($miArray,[
                "idCoche" => $idCoche, 
                "Vendedores_idVendedor" => $Vendedores_idVendedor, 
                "Compradores_idComprador" => $Compradores_idComprador, 
                "marca" => $marca, 
                "modelo" => $modelo, 
                "combustible" => $combustible, 
                "color" => $color, 
                "precio" => $precio
            ]);
        }
       
        // Cierro conexión
        $conexion->close();

        return $arrayCoches;
    }

    // VENDEDORES - SELECT
    public function obtenerVendedores()
    {
        // Establece la conexión
        $conexion = $this->crearConexion();

        // Prepara una plantilla de la sentencia SQL 
        $prepare = $conexion->prepare("SELECT idVendedor, Personas_idPersona FROM Vendedores");

        // Comprueba si hay Vendedores
        if (!$prepare) {
            echo "Comprueba si hay vendedores: ";
            //var_dump($conexion->error_list); /*** */
            throw new Exception($conexion->error_list);
        }

        // Ejecuta la sentencia
        $prepare->execute();

        // Vincula variables a una sentencia preparada para el almacenamiento de resultados
        $prepare->bind_result($idVendedor, $Personas_idPersona);

        // Obtiene los resultados de una sentencia preparada en las variables vinculadas
        $miArray = array();
        while ($prepare->fetch()) {
            array_push($miArray,[
                "idVendedor" => $idVendedor, 
                "Personas_idPersona" => $Personas_idPersona
            ]);
        }
       
        // Cierro conexión
        $conexion->close();

        return $miArray;
    }
    
    // COMPRADORES - SELECT
    public function obtenerCompradores()
    {
        // Establece la conexión
        $conexion = $this->crearConexion();

        // Prepara una plantilla de la sentencia SQL 
        //select idComprador, nombre, apellidos, dni from Personas join Compradores on Personas.idPersona = Compradores.idComprador;
        $prepare = $conexion->prepare("SELECT idComprador, Personas_idPersona FROM Compradores");

        // Comprueba si hay Compradores
        if (!$prepare) {
            echo "Comprueba si hay compradores: ";
            $this->showPre($conexion->error_list);
            throw new Exception($conexion->error_list);
        }

        // Ejecuta la sentencia
        $prepare->execute();

        // Vincula variables a una sentencia preparada para el almacenamiento de resultados
        $prepare->bind_result($idComprador, $Personas_idPersona);

        // Obtiene los resultados de una sentencia preparada en las variables vinculadas
        $miArray = array();
        while ($prepare->fetch()) {
            array_push($miArray,[
                "idComprador" => $idComprador, 
                "Personas_idPersona" => $Personas_idPersona
            ]);
        }
        //echo "obtenerCompradores: " . "<br />"; /*** */
        //$this->showPre($miArray); /*** */
       
        // Cierro conexión
        $conexion->close();

        return $miArray;
    }
    
    // PERSONAS - SELECT
    public function obtenerPersonas()
    {
        // Establece la conexión
        $conexion = $this->crearConexion();

        // Prepara una plantilla de la sentencia SQL 
        $prepare = $conexion->prepare("SELECT idPersona, nombre, apellidos, dni FROM Personas");

        // Comprueba si hay 
        if (!$prepare) {
            echo "Comprueba si hay personas: ";
            $this->showPre($conexion->error_list);
            throw new Exception($conexion->error_list);
        }

        // Ejecuta la sentencia
        $prepare->execute();

        // Vincula variables a una sentencia preparada para el almacenamiento de resultados
        $prepare->bind_result($idPersona, $nombre, $apellidos, $dni);

        // Obtiene los resultados de una sentencia preparada en las variables vinculadas
        $miArray = array();
        while ($prepare->fetch()) {
            array_push($miArray,[
                "id" => $idPersona, 
                "nombre" => $nombre, 
                "apellidos" => $apellidos, 
                "dni" => $dni
            ]);
        }
        //var_dump($miArray);
       
        // Cierro conexión
        $conexion->close();

        return $miArray;
    }
    
    /**
     * Permite ver los datos formatados en HTML.
     * Usar cuando var_dump no ofrece una legibilidad adecuada.
     * 
     * @param $dato datos compuestos como arrays y arrays de llave-valor
     */
    private function showPre($dato)
    {
        echo '<pre>';
        print_r($dato);
        echo '</pre>';
    }
}
?>