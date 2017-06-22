<?php

    /**********************************************************************************
        index.php es el gestor principal de todos los controladores
        es el encargado de cargar la apariencia de la pagina, y el controlador actual 
    **********************************************************************************/
    
    // Esto le dice a PHP que usaremos cadenas UTF-8 hasta el final
    mb_internal_encoding('UTF-8');

    // Esto le dice a PHP que generaremos cadenas UTF-8
    mb_http_output('UTF-8');

    // para obtnener fechas
    date_default_timezone_set("America/Santiago");
    
    // se inicia el archivo de configuraciones
    require 'config.php';

    // para auto-cargar las librerias necesarias
    spl_autoload_register(function($class){
       if (file_exists(LIBS.$class.".php")){
           require LIBS.$class.".php";
       } 
    });
    
    // se selecciona el controlador adecuado dado por metodo get
    // a traves de la url limpia de la forma controlador/metodo/parametro
    $url = (isset($_GET["url"])) ? $_GET["url"] : "Login";
    $url = explode("/",$url);
  
    // para obtener urls limpias
    if (isset($url[0])) $controlador = $url[0]."Control";
    if (isset($url[1])) {if ($url[1] != '') $metodo = $url[1];}
    if (isset($url[2])) {if ($url[2] != '') $parametro = $url[2];}
    
    // obtenemos el path al controlador que se requiere
    $path = "./controladores/".$controlador.".php";

    // si el controlador existe
    if (file_exists($path))
    {
        // cargamos al controlador
        require $path;
        $controlador = new $controlador();

        // si hemos ingresado un metodo
        if (isset($metodo))
        {
            // si el metodo existe en el controlador
            if (method_exists($controlador, $metodo))
            {
                // si hemos ingresado un parametro
                if (isset($parametro))
                {
                    // cargamos el metodo del controlador con el parametro definido
                    $controlador->{$metodo}($parametro);
                } 
                // de lo contrario solo cagramos el metodo sin parametros
                else {
                    $controlador->{$metodo}();
                }
            }
            // si el metodo no existe en el controlador, cargamos el metodo index por defecto
            else if (method_exists($controlador,"index")){
                $controlador->index();
            }
        }
        // si el metodo no ha sido definido, cargamos el metodo index por defecto
        else if (method_exists($controlador,"index")){
            $controlador->index();
        }
        // si no tiene metodos definidos, mostramos error
        else {
            echo "Error : El controlador no esta bien definido";
        }
    } 
    // el controlador no existe
    else {
        echo "Error : El controlador no existe";
        echo $path;
    }