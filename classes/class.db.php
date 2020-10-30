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

        // No contiene los almacenados en BD, sólo los de memoria temporal de programa /*** */
        $vendedor_array = array();
        $vendedor_array[] = $id;
        //var_dump($vendedor_array);
        //$this->showPre($vendedor_array);

        // Recoge los id de los vendedores en un array que proporciona la consulta a la BD
        $vendedorIds = $this->obtenerVendedores();
        //var_dump($vendedorIds);
        //$this->showPre($vendedorIds);

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
     * @param string $matricula, $marca, $modelo, $combustible, $color, 
     * @param int $precio
     */
    // idVendedor, idComprador, matricula, marca, modelo, combustible, color, precio
    public function enviarCoche($datos, $Vendedores_idVendedor, $matricula, $marca, $modelo, $combustible, $color, $precio)
    {
        $conexion = $this->crearConexion();
        $enviarCoche = $conexion->prepare("INSERT INTO Coches(
            Vendedores_idVendedor, 
            matricula, 
            marca, 
            modelo, 
            combustible, 
            color, 
            precio
            ) VALUES (?, ?, ?, ?, ?, ?, ?);");

        $enviarCoche->bind_param(
            $datos,
            $Vendedores_idVendedor,
            $matricula, 
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
     * @param int $Vendedores_idVendedor, $Compradores_idComprador, $matricula
     * @param date $created_at fecha de realización de la transacción
     */
    // idVendedores, idCompradores, created_at, matricula
    public function enviarTransaccion($datos, $Vendedores_idVendedor, $Compradores_idComprador, $created_at)
    {
        //echo "antes:";
        //$this->showPre($created_at);
        
        $conexion = $this->crearConexion();
        $enviarTransaccion = $conexion->prepare("INSERT INTO Transacciones(
            Vendedores_idVendedor, 
            Compradores_idComprador, 
            created_at
            ) VALUES (?, ?, ?)");
        $enviarTransaccion->bind_param(
            $datos,
            $Vendedores_idVendedor,
            $Compradores_idComprador,
            $created_at = date_format(date_create($created_at), 'Y-m-d') //convierte fecha de formato europeo a SQL (Y-m-d)
        );

        //echo "despues:";
        //$this->showPre($created_at);

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

        //echo "id = enviarTransaccion->insert_id " ;
        //$this->showPre($id);

        // Cierro conexión
        $enviarTransaccion->close();

        // Devuelvo el ID
        return $id;
    }

    // TRANSACCIONES_has_COCHES - INSERT
    /**
     * 
     */
    // Transacciones_idTransaccion, Coches_idCoche
    public function enviarTransaccionCoche($datos, $idTransaccion, $idCoche)
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
        $id = $enviarTransaccionCoche->insert_id-1;

        // Cierro conexión
        $enviarTransaccionCoche->close();

        // Devuelvo el ID
        return $id;
    }

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
            Vendedores_idVendedor as idVendedor, 
            matricula, 
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
            $matricula, 
            $marca, 
            $modelo, 
            $combustible, 
            $color, 
            $precio
        );

        // Obtiene los resultados de una sentencia preparada en las variables vinculadas
        $arrayCoches = array();
        while ($prepare->fetch()) {
            array_push($arrayCoches,[
                "idCoche"               => $idCoche, 
                "idVendedor"            => $Vendedores_idVendedor, 
                "matricula"             => $matricula, 
                "marca"                 => $marca, 
                "modelo"                => $modelo, 
                "combustible"           => $combustible, 
                "color"                 => $color, 
                "precio"                => $precio
            ]);
        }
       
        // Cierro conexión
        $conexion->close();

        return $arrayCoches;
    }

    // VENDEDORES - SELECT
    /**
     * Realiza una consulta en la tabla Vendedores
     * 
     * @return array $arrayVendedores devuelve un array con los elementos que forman parte de la consulta
     */
    public function obtenerVendedores()
    {
        // Establece la conexión
        $conexion = $this->crearConexion();

        // Prepara una plantilla de la sentencia SQL 
        //SELECT idVendedor, Personas_idPersona FROM Vendedores
        $prepare = $conexion->prepare("SELECT 
            idVendedor, 
            nombre, 
            apellidos, 
            dni 
            FROM 
            Personas 
            JOIN 
            Vendedores 
            ON 
            Personas.idPersona = Vendedores.Personas_idPersona");

        // Comprueba si hay Vendedores
        if (!$prepare) {
            echo "Comprueba si hay vendedores: ";
            //var_dump($conexion->error_list); /*** */
            throw new Exception($conexion->error_list);
        }

        // Ejecuta la sentencia
        $prepare->execute();

        // Vincula variables a una sentencia preparada para el almacenamiento de resultados
        $prepare->bind_result($idVendedor, $nombre, $apellidos, $dni);

        // Obtiene los resultados de una sentencia preparada en las variables vinculadas
        $arrayVendedores = array();
        while ($prepare->fetch()) {
            array_push($arrayVendedores,[
                "idVendedor"    => $idVendedor, 
                "nombre"        => $nombre,
                "apellidos"     => $apellidos,
                "dni"           => $dni
            ]);
        }
       
        // Cierro conexión
        $conexion->close();

        return $arrayVendedores;
    }
    
    // COMPRADORES - SELECT
    /**
     * Realiza una consulta en la tabla Compradores
     * 
     * @return array $arrayCompradores devuelve un array con los elementos que forman parte de la consulta
     */
    public function obtenerCompradores()
    {
        // Establece la conexión
        $conexion = $this->crearConexion();

        // Prepara una plantilla de la sentencia SQL 
        //SELECT idComprador, Personas_idPersona FROM Compradores
        //select idComprador, nombre, apellidos, dni from Personas join Compradores on Personas.idPersona = Compradores.Personas_idPersona
        $prepare = $conexion->prepare("SELECT 
            idComprador, 
            nombre, 
            apellidos, 
            dni 
            FROM 
            Personas 
            JOIN 
            Compradores 
            ON 
            Personas.idPersona = Compradores.Personas_idPersona;");

        // Comprueba si hay Compradores
        if (!$prepare) {
            echo "Comprueba si hay compradores: ";
            $this->showPre($conexion->error_list);
            throw new Exception($conexion->error_list);
        }

        // Ejecuta la sentencia
        $prepare->execute();

        // Vincula variables a una sentencia preparada para el almacenamiento de resultados
        $prepare->bind_result($idComprador, $nombre, $apellidos, $dni);

        // Obtiene los resultados de una sentencia preparada en las variables vinculadas
        $arrayCompradores = array();
        while ($prepare->fetch()) {
            array_push($arrayCompradores,[
                "idComprador"   => $idComprador, 
                "nombre"        => $nombre,
                "apellidos"     => $apellidos,
                "dni"           => $dni

            ]);
        }
        //echo "obtenerCompradores: " . "<br />"; /*** */
        //$this->showPre($arrayCompradores); /*** */
       
        // Cierro conexión
        $conexion->close();

        return $arrayCompradores;
    }
    
    // PERSONAS - SELECT
    /**
     * Realiza una consulta en la tabla Personas
     * 
     * @return array $arrayPersonas devuelve un array con los elementos que forman parte de la consulta
     */
    public function obtenerPersonas()
    {
        // Establece la conexión
        $conexion = $this->crearConexion();

        // Prepara una plantilla de la sentencia SQL 
        $prepare = $conexion->prepare("SELECT 
            idPersona, 
            nombre, 
            apellidos, 
            dni 
            FROM 
            Personas");

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
        $arrayPersonas = array();
        while ($prepare->fetch()) {
            array_push($arrayPersonas,[
                "idPersona" => $idPersona, 
                "nombre"    => $nombre, 
                "apellidos" => $apellidos, 
                "dni"       => $dni
            ]);
        }
        //var_dump($arrayPersonas);
       
        // Cierro conexión
        $conexion->close();

        return $arrayPersonas;
    }

    // TRANSACCIONES - SELECT
    /**
     * Realiza una consulta en la tabla Transacciones
     * 
     * @return array $arrayPersonas devuelve un array con los elementos que forman parte de la consulta
     */
    public function obtenerTransacciones()
    {
        // Establece la conexión
        $conexion = $this->crearConexion();

        // Prepara una plantilla de la sentencia SQL 
        $prepare = $conexion->prepare("SELECT 
            t.idTransaccion,
            t.Vendedores_idVendedor AS idVendedor,
            t.Compradores_idComprador AS idComprador,
            c.matricula,
            t.created_at AS fecha
            FROM Transacciones AS t
            JOIN Transacciones_has_Coches AS tc 
            ON t.idTransaccion = tc.Transacciones_idTransaccion 
            JOIN Coches as c 
            ON tc.Coches_idCoche = c.idCoche
            ORDER BY t.idTransaccion;");

        // Comprueba si hay 
        if (!$prepare) {
            echo "Comprueba si hay transacciones: ";
            $this->showPre($conexion->error_list);
            throw new Exception($conexion->error_list);
        }

        // Ejecuta la sentencia
        $prepare->execute();

        // Vincula variables a una sentencia preparada para el almacenamiento de resultados
        $prepare->bind_result(
            $idTransaccion, 
            $idVendedor, 
            $idComprador, 
            $matricula, 
            $created_at);

        // Obtiene los resultados de una sentencia preparada en las variables vinculadas
        $arrayTransacciones = array();
        while ($prepare->fetch()) {
            array_push($arrayTransacciones,[
                "idTransaccion"             => $idTransaccion, 
                "idVendedor"                => $idVendedor, 
                "idComprador"               => $idComprador, 
                "matricula"                 => $matricula, 
                "fecha"                     => $created_at
            ]);
        }
        //var_dump($arrayTransacciones);
       
        // Cierro conexión
        $conexion->close();

        return $arrayTransacciones;
    }

    //  COCHES Y VENDEDORES- SELECT
    /**
     * Realiza una consulta en la tabla Coches para mostrar sólo los coches que tienen para vender.
     * 
     * @return array $arrayCochesVendedores devuelve un array con los elementos que forman parte de la consulta
     */
    public function obtenerCochesVendedores()
    {
        // Establece la conexión
        $conexion = $this->crearConexion();

        // Prepara una plantilla de la sentencia SQL 
        //SELECT idCoche, matricula from Coches where idCoche not in (select Coches_idCoche from Transacciones_has_Coches join Coches where Coches_idCoche = idCoche);
        $prepare = $conexion->prepare("SELECT 
            idCoche, 
            matricula 
            FROM Coches WHERE 
            idCoche 
            NOT IN (SELECT 
                Coches_idCoche
                FROM Transacciones_has_Coches);");

        // Comprueba si hay Vendedores
        if (!$prepare) {
            echo "Comprueba si hay vendedores: ";
            //var_dump($conexion->error_list); /*** */
            throw new Exception($conexion->error_list);
        }

        // Ejecuta la sentencia
        $prepare->execute();

        // Vincula variables a una sentencia preparada para el almacenamiento de resultados
        $prepare->bind_result(
            $idCoche, 
            $matricula);

        // Obtiene los resultados de una sentencia preparada en las variables vinculadas
        $arrayCochesVendedores = array();
        while ($prepare->fetch()) { 
            //Los únicos parámetros que se necesitan son los campos que se piden
            array_push($arrayCochesVendedores,[
                "idCoche"           => $idCoche, 
                "matricula"         => $matricula
            ]);
        }
       
        // Cierro conexión
        $conexion->close();

        return $arrayCochesVendedores;
    }
    
    // VENDEDORES Y COCHES - SELECT
    /**
     * Realiza una consulta en la tabla Vendedores para mostrar sólo los que tienen coches para vender
     * 
     * @return array $arrayVendedores devuelve un array con los elementos que forman parte de la consulta
     */
    public function obtenerVendedoresCoches()
    {
        // Establece la conexión
        $conexion = $this->crearConexion();

        // Prepara una plantilla de la sentencia SQL 
        //SELECT idVendedor, Personas_idPersona FROM Vendedores
        $prepare = $conexion->prepare("SELECT 
            idVendedor, 
            dni 
            FROM Vendedores, Personas 
            WHERE Personas_idPersona = idPersona AND idVendedor IN (SELECT 
                Vendedores_idVendedor FROM Coches WHERE idCoche NOT IN (SELECT 
                    Coches_idCoche FROM Transacciones_has_Coches));");

        // Comprueba si hay Vendedores
        if (!$prepare) {
            echo "Comprueba si hay vendedores: ";
            //var_dump($conexion->error_list); /*** */
            throw new Exception($conexion->error_list);
        }

        // Ejecuta la sentencia
        $prepare->execute();

        // Vincula variables a una sentencia preparada para el almacenamiento de resultados
        $prepare->bind_result(
            $idVendedor, 
            $dni);

        // Obtiene los resultados de una sentencia preparada en las variables vinculadas
        $arrayVendedoresCoches = array();
        while ($prepare->fetch()) {
            array_push($arrayVendedoresCoches,[
                "idVendedor"    => $idVendedor, 
                "dni"           => $dni
            ]);
        }
       
        // Cierro conexión
        $conexion->close();

        return $arrayVendedoresCoches;
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