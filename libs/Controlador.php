<?php

// Clase controlador, común para todos los controladores
class Controlador {
            
    // conexion
    protected $db;
    
    function __construct() 
    {
        Sesion::init();
        $this->state = str_replace("Control", "", get_class($this));
        $this->vista = new Vista();
        $this->load_model();
    }
    
    // metodo index común para todos
    function index() {
        $this->vista->render($this->state);
    }
    
    // cargar el modelo al controlador
    function load_model()
    {
        $modelo = $this->state."Modelo";
        $path = "./modelos/".$modelo.".php";
        
        if (file_exists($path)) {
            require $path;
            $this->modelo = new $modelo();
        }
    }

    // destruir sesión
    function close() {
        Sesion::destroy();
    }

    // para retornar un mensaje de exito o de error
    static function success($mensaje = "") {
        echo json_encode([1,$mensaje]);
    }
    static function error($mensaje = "") {
        echo json_encode([0,$mensaje]);
    }

    // para condicionar la entrada de un metodo
    static function input(...$entrada) {
        $resultado = true;
        if (count($entrada) > 0) {
            foreach ($entrada as $variable) {
                $resultado = ($resultado && isset($_POST[$variable]));
            }
        } 
        $resultado = ($resultado && isset($_POST["llave"]));
        return $resultado;
    }
}

