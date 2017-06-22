<?php

/**********************************************************************************
    config.php tiene todas las variables globales del sistema
**********************************************************************************/

define("DB_HOST",$_SERVER["HTTP_HOST"]);
define("DB_USER","fabian");
define("DB_PASS","YmmaFPA62");
define("DB_NAME","bdmanutex");

define("URL","http://".DB_HOST."/Manutex/");
define("BOOTSTRAP",URL."public/bootstrap/");
define("DATATABLE",URL."public/datatable/");
define("PDFMAKE",URL."public/pdfmake/");
define("MAIN",URL."public/main/");
define("MAIN_JS",URL."public/main/js/");
define("MAIN_CSS",URL."public/main/css/");
define("JQUERY", MAIN."js/jquery-3.1.0.min.js");
define("ANGULAR", MAIN."js/angular.min.js");
define("DATERANGEPICKER", MAIN."js/daterangepicker.js");
define("MOMENT", MAIN."js/moment.min.js");

define("LIBS","libs/");

/****************************************************
correo respaldo : manutex.respaldo@gmail.com
contraseña: EcUR71bD
****************************************************/

?>
