<?php
class Formulario {
    // Propiedades (o variables, atributos, campos) de la clase:
    // Datos
    public $datosRecibidos;
    public $tiposValidos = ['text', 'number', 'checkbox', 'select'];
    // Errores
    public $errores;
    public $mensajes_error;
    // Directorios
    public $path_media;
    public $dir_subida;
    public $dir_proyecto;
    // Media
    public $array_mime_types;
    public $array_extensiones_permitidas;
    public $fotoRecibida;

    public function __construct()
    {
        $this->errores = null;
        $this->dir_subida = getcwd() . '/tmp/'; // /assets/img/
        $this->dir_proyecto = '/tmp/';
        $this->array_mime_types = array('image/png', 'image/jpg', 'image/jpeg', 'image/gif', 'image/svg', 'image/webp', 'image/ico');
        $this->array_extensiones_permitidas = array('png', 'jpg', 'jpeg', 'gif', 'svg', 'webp', 'ico');
        $this->array_personas_validas = array(
            'comprador' => 'Comprador', 
            'vendedor'  => 'Vendedor'
        );
    }

    /**
     * Envia el formulario con los datos i/o ficheros
     * 
     * @param string $datos datos introducidos en el formulario
     * @param array $files ficheros introducidos en el formulario
     */
    public function enviarFormulario($datos, $files=null)
    {
        //echo "<pre>"; /*** */
        //print_r("FORM-Ha entrat en enviarFormulario, "); /*** */

        $this->datosRecibidos = $datos;
        //print_r("guardat dades "); /*** */

        // Utilizar la función reset(); me permite coger el primer valor de un array asociativo.
        if  ($files !== null) // (!empty($files))
        {
            $this->fotoRecibida = reset($files);
            //print_r("i guardat fitxer"); /*** */
        }
        //echo "</pre>"; /*** */
    }

    /**
     * Valida los tipos habilitados para el formulario
     * 
     * Valida los tipos recibidos, que deberán corresponder con los habilitados para el formulario
     * @param string $type tipo de los datos que se han recibido
     * @param array $tiposValidos tipos que se han habilitado como válidos en el formulario
     * 
     * @return boolean
     */
    private function validarTipo($type)
    {
        if (in_array($type, $tiposValidos)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Recibe los campos del formulario, comprueba su equivalencia y muestra HTML.
     * 
     * Recibe los campos del formulario y, según el tipo, comprueba que sea el equivalente y muestra HTML 
     * indicando las etiquetas correspondientes y mostrando mensaje final.
     * @param string $type, $id, $name, $placeholder, $label
     * @param boolean $validacion 
     * @param string $options opciones disponibles en el selector del input
     * @param array $multiple indica si se han insertado múltiples archivos
     */
    public function showInput($type, $id, $name, $placeholder, $label, $validacion, $options=null, $multiple=null)
    {
        //$this->showPre("FORM-Entra en showInput"); /*** */
        switch ($type) {
            case 'text':
                return $this->getTypeInput($type, $id, $name, $placeholder, $label, $validacion);
                break;
            
            case 'number':
                return $this->getTypeInputNumber($type, $id, $name, $placeholder, $label, $validacion);
                break;

            case 'checkbox':
                return $this->getTypeCheckbox($type, $id, $name, $placeholder, $label, $validacion);
                break;

            case 'select':
                return $this->getTypeSelect($type, $id, $name, $placeholder, $label, $validacion, $options, $multiple);
                break;

            case 'file':
                return $this->getTypeFile($type, $id, $name, $placeholder, $label, $validacion);
                break;

            default:
                # code...
                break;
        }
    }

    // TIPO TEXTO
    /**
     * Comprueba que el tipo entrado en el formulario sea texto.
     * 
     * @param string $type, $id, $name, $placeholder, $label
     * @param boolean $validacion 
     */
    private function getTypeInput($type, $id, $name, $placeholder, $label, $validacion)
    {
        //$this->showPre("FORM-Entra en getTypeInput"); /*** */
        // Reseteo las variables
        $classes = "input input-text";
        $miDato = "";
        $esValido = null;

        // Si el objeto existe y estamos en método POST
        if ($validacion) {
            // Sanitizo los datos recibidos, comprobando valor del campo con el tipo
            $miDato = $this->sanitizacion($this->datosRecibidos[$name], $type);
            //$this->showPre("FORM-Resultado de sanitizado: " . $miDato); /*** */
            $esValido = $this->validacion($miDato, $type);
            //$this->showPre("FORM-Resultado de validacion: " . $esValido); /*** */

            if ($esValido) {
                $classes .= " valid-input";
            } else {
                $classes .= " error-input";
                $this->errores = true;
                $this->showPre("FORM-Activación de error: " . $this->errores); /*** */
            }
        }

        $textInput = '<div class="grupo">';
        $textInput .= '<label class="label" for="' . $id . '">';
        $textInput .= $label;
        $textInput .= '</label>';
        $textInput .= '<input value="' . $miDato . '" type="text" name="' . $name . '" id="' . $id . '" placeholder="' . $placeholder . '" class="' . $classes . '" />';

        if ($miDato && $esValido) {
            $textInput .= '<p class="success small">Datos validos.</p>';
        }

        if ($esValido === false) {
            $textInput .= '<p class="error small">Por favor, revisa el campo. El dato esta vacio o no es valido.</p>';
        }

        $textInput .= '</div>';
        echo $textInput;
    }

    // TIPO NÚMERO
    /**
     * Comprueba que el tipo entrado en el formulario sea numérico.
     * 
     * @param string $type, $id, $name, $placeholder, $label
     * @param boolean $validacion 
     */
    private function getTypeInputNumber($type, $id, $name, $placeholder, $label, $validacion)
    {
        //$this->showPre("FORM-Entra en getTypeInputNumber"); /*** */
        $classes = "input input-number";
        $miDato = "";
        $esValido = null;

        if ($validacion) {
            $miDato = $this->sanitizacion($this->datosRecibidos[$name], $type);
            $esValido = $this->validacion($miDato, $type);

            if ($esValido) {
                $classes .= " valid-input";
            } else {
                $classes .= " error-input";
                $this->errores = true;
            }
        }

        $textInput = '<div class="grupo">';
        $textInput .= '<label class="label" for="' . $id . '">';
        $textInput .= $label;
        $textInput .= '</label>';
        $textInput .= '<input value="' . $miDato . '" type="number" name="' . $name . '" id="' . $id . '" placeholder="' . $placeholder . '" class="' . $classes . '" />';

        if ($miDato && $esValido) {
            $textInput .= '<p class="success small">Datos validos.</p>';
        }

        if ($esValido === false) {
            $textInput .= '<p class="error small">Por favor, revisa el campo. El dato esta vacio o no es valido.</p>';
        }

        $textInput .= '</div>';
        echo $textInput;
    }

    // TIPO FICHERO
    /**
     * Comprueba que el tipo entrado en el formulario sea fichero.
     * 
     * @param string $type, $id, $name, $placeholder, $label
     * @param boolean $validacion 
     */
    private function getTypeFile($type, $id, $name, $placeholder, $label, $validacion)
    {
        //$this->showPre("FORM-Entra en getTypeFile"); /*** */
        $classes = "input input-file";
        $miDato = "";
        $esValido = null;
        var_dump($this->fotoRecibida);
        
        if ($validacion && $this->fotoRecibida)
        {
            $fichero_subido = $this->dir_subida . basename($this->fotoRecibida['name']);
            $this->path_media = $this->dir_proyecto . basename($this->fotoRecibida['name']);
            $fichero_extension = pathinfo($fichero_subido, PATHINFO_EXTENSION);

            if (
                !in_array($this->fotoRecibida['type'], $this->array_mime_types) ||
                !in_array($fichero_extension, $this->array_extensiones_permitidas)
            ) {

                //$this->showPRE($this->fotoRecibida['type']); /*** */
                //$this->showPRE($fichero_extension); /*** */
                //$this->showPRE($this->array_mime_types); /*** */
                //$this->showPRE($this->array_extensiones_permitidas); /*** */

                $classes .= " error-input";
                $this->errores = true;
                throw new Exception("Hay un error de validación con el fichero que has seleccionado");
                return "";
            }

            $nuevoNombre = $this->escanearDirectorio(basename($this->fotoRecibida['name']));

            move_uploaded_file($this->fotoRecibida['tmp_name'], $fichero_subido);
            $classes .= " valid-input";
        }

        $textInput = '<div class="grupo">';
        $textInput .= '<label class="label" for="' . $id . '">';
        $textInput .= $label;
        $textInput .= '</label>';
        $textInput .= '<input type="file" name="' . $name . '" id="' . $id . '" alt="' . $name . '" placeholder="' . $placeholder . '" class="' . $classes . '" accept="image/png, image/jpg, image/jpeg, image/gif", image/webp/>';

        if ($miDato && $esValido) {
            $textInput .= '<p class="success small">Datos validos.</p>';
        }

        if ($esValido === false) {
            $textInput .= '<p class="error small">Por favor, revisa el campo. El dato esta vacio o no es valido.</p>';
        }

        $textInput .= '</div>';
        echo $textInput;
    }

    // TIPO CHECKBOX
    /**
     * Comprueba que el tipo entrado en el formulario sea checkbox.
     * 
     * @param string $type, $id, $name, $placeholder, $label
     * @param boolean $validacion 
     */
    private function getTypeCheckbox($type, $id, $name, $placeholder, $label, $validacion)
    {
        //$this->showPre("FORM-Entra en getTypeCheckbox"); /*** */
        $classes = "input input-checkbox";
        $isChecked = "";

        if ($validacion && in_array($name, array_keys($this->datosRecibidos))) {
                $isChecked = "checked";
                $classes .= " valid-input";
        }

        $checkBox = '<div class="grupo grupo-checkbox">';
        $checkBox .= '<input ' . $isChecked . ' type="checkbox" name="' . $name . '" id="' . $id . '" placeholder="' . $placeholder . '" class="' . $classes . '"/>';
        $checkBox .= '<label class="label" for="' . $id . '">';
        $checkBox .= $label;
        $checkBox .= '</label>';

        // if ($esValido) {
        //     $checkBox .= '<p class="success small">Datos validos.</p>';
        // } else {
        //     $checkBox .= '<p class="error small">Por favor, revisa el campo. El dato esta vacio o no es valido.</p>';
        // }

        $checkBox .= '</div>';
        echo $checkBox;
    }

    // TIPO SELECTOR
    /**
     * Comprueba que el tipo entrado en el formulario sea selector.
     * 
     * @param string $type, $id, $name, $placeholder, $label
     * @param boolean $validacion 
     * @param array $options opciones de selección
     * @param array $multiple ficheros insertados
     */
    private function getTypeSelect($type, $id, $name, $placeholder, $label, $validacion, $options, $multiple)
    {
        //$this->showPre("FORM-Entra en getTypeSelect"); /*** */
        $classes = "input input-select";
        $mensaje_validacion = "";
        $isSelected = false;
        $valor_seleccionado = "";
        
        if ($multiple) {
            $valor_seleccionado = array();
        }

        if ($validacion && in_array(str_replace('[]', '', $name), array_keys($this->datosRecibidos))) {
            $valor_seleccionado = $this->datosRecibidos[str_replace('[]', '', $name)];

            if ($multiple) {
                $arrayValoresSeleccionados = array_values($valor_seleccionado);
                $arrayEnviado = array_keys($options);
                $resultado = array_intersect($arrayValoresSeleccionados, $arrayEnviado);
                
                if (count($resultado) === count($arrayValoresSeleccionados)) {
                    $classes .= " valid-input";
                    $mensaje_validacion = '<p class="success small">Datos validos.</p>';
                    $isSelected = true;
                } else {
                    $classes .= " error-input";
                    $mensaje_validacion = '<p class="error small">Alguno de los datos esta mal, por favor revisa los datos seleccionados.</p>';
                    $this->errores = true;
                }
            } else {

                if (in_array($valor_seleccionado, array_keys($options))) {
                    $classes .= " valid-input";
                    $mensaje_validacion = '<p class="success small">Datos validos.</p>';
                    $isSelected = true;
                } else {
                    $classes .= " error-input";
                    $mensaje_validacion = '<p class="error small">Alguno de los datos esta mal, por favor revisa los datos seleccionados.</p>';
                    $this->errores = true;
                }
            }
        }

        $select = '<div class="grupo grupo-select">';
        $select .= '<label class="label" for="' . $id . '">';
        $select .= $label;
        $select .= '</label>';
        $select .= '<select ' . ($multiple ? 'multiple' : '') . ' id="' . $id . '" name="' . $name . '" class="' . $classes . '">';
        $select .= '<option disabled' . ($isSelected === false ? ' selected' : "") . '>-- Por favor seleccionar una opción</option>';

        foreach ($options as $key => $value) {
            if ($multiple) {
                $select .= '<option value="' . $key . '"' . (in_array($key, array_values($valor_seleccionado)) ? ' selected' : "") . '>' . $value . '</option>';
            } else {
                $select .= '<option value="' . $key . '"' . ($valor_seleccionado === $key ? ' selected' : "") . '>' . $value . '</option>';
            }
        }

        $select .= '</select>';
        $select .= $mensaje_validacion;
        $select .= '</div>';
        echo $select;
    }

    /**
     * Asigna el filtro de sanitización correspondiente al tipo recibido.
     * 
     * Según el tipo recibido asigna el filtro de sanitización correspondiente. 
     * Devuelve el resultado del filtrado de la variable con el filtro asignado, que en caso de ser 
     * positivo seran los propios datos i en caso contrario el booleano false.
     * @param string $valor nombre del campo
     * @param string $tipo tipo del campo
     * 
     * @return filter_var devuelve los datos filtrados o false
     */
    private function sanitizacion($valor, $tipo)
    {
        switch ($tipo) {
            case 'text':
                // Elimina etiquetas, opcionalmente elimina o codifica caracteres especiales.
                $filter = FILTER_SANITIZE_STRING;
                break;

            case 'number':
                // Elimina todos los caracteres excepto dígitos y los signos de suma y resta.
                $filter = FILTER_SANITIZE_NUMBER_INT;
                break;
            
            default:
                # code...
                break;
        }
        return filter_var($valor, $filter);
    }

    /**
     * Comprueba que hay un valor según el tipo
     * 
     * Según el tipo recibido devuelve el resultado booleano de comparar el valor 
     * con una falta (valor vacío o inexistencia) de dato.
     * @param string o booleano o número $valor nombre del campo una vez sanitizado
     * @param string $tipo tipo del campo original (sin sanitizado)
     * 
     * @return boolean 
     */
    private function validacion($valor, $tipo)
    {
        switch ($tipo) {
            case 'text':
                return $valor !== '' ? true : false;
                break;

            case 'number':
                return $valor !== '' ? true : false;
                break;
            
            default:
                # code...
                break;
        }
    }

    /**
     * Devuelve el booleano que contenga la propiedad errores
     * 
     * @return boolean
     */
    public function hayErrores()
    {
        //$this->showPre("FORM-Errores: " . $this->errores); /*** */
        return $this->errores;
    }

    /**
     * Obtiene el directorio donde se encuentra el proyecto
     * 
     * @param string $nombreArchivo nombre del archivo del proyecto
     */
    private function escanearDirectorio($nombreArchivo)
    {
        $ficherosDirectorio = scandir($this->dir_subida);
        //$this->showPre("FORM-Entra en escanearDirectorio: " . $nombreArchivo); // $this->showPre //*** */
        //$this->showPre($ficherosDirectorio); // $this->showPre //*** */

        if (in_array($nombreArchivo, $ficherosDirectorio)) {
            echo "ESTOY EN EL DIRECTORIO NO ME GUARDES";
        } else {
            echo "GUARDAMEEEEE";
        }
    }

    /**
     * Muestra en el navegador los datos de intercambio de una forma más clara.
     * Es una alternativa a var_dump.
     * 
     * @param array $dato contiene los datos recibidos
     */
    private function showPre($dato)
    {
        echo '<pre>';
        print_r($dato);
        echo '</pre>';
    }
}
