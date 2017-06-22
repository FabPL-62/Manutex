<?php
class PrincipalControl extends Controlador {
    
    function __construct() {
        parent::__construct();
    }

    function salir() {
    	if (Controlador::input("login_rut")) {

    		// se obtiene el rut
    		$rut = explode("-",$_POST["login_rut"])[0];

    		// se desconecta a la socia
    		$resultado = $this->modelo->salir($rut);

    		// se destruye la sesion
    		if ($resultado == 1) {
    			Sesion::destroy();
    			echo 1;
    		}
    	}
    }

    // para cargar una subvista en la vista principal
    function cargar($vista)
    {
        // para cargar, debe haber una llave
        if (Controlador::input())
        {
            // obtenemos el archivo de la vista
            $path = "./vistas/".$vista."/index.php";
            
            // la vista debe existir
            if (file_exists($path))
            {
                if (Controlador::input())
                {
                    // agregamos estas dos lineas
                    echo "<script src='".DATERANGEPICKER."'></script>\n";
                    echo "<script src='".MOMENT."'></script>\n";

                    // se carga la vista
                    require $path;
                    echo PHP_EOL;
                    echo "<script src='".URL."vistas/$vista/main.js'></script>";
                }
            }
        }
    }

    // para respaldar la base de datos
    function respaldar()
    {
        if (Controlador::input("respaldar"))
        {
            $backup_file = "C:/xampp/backups/manutex-".date("Y-m-d-H-i-s").".sql";

            $host = DB_HOST;
            $username = DB_USER;
            $password = DB_PASS;
            $dbName = DB_NAME;

            $command = "C:/xampp/mysql/bin/mysqldump --opt -h $host -u $username -p$password $dbName > $backup_file";
            system($command,$output);

            $to = "manutex.respaldo@gmail.com";
            $from = "manutex.respaldo@gmail.com";
            $subject = "Respaldo Manutex " . date("Y-m-d-H-i-s");
            $separator = md5(time());
            $filename = date("Y-m-d-H-i-s").'.sql';
            $attachment = chunk_split(base64_encode(file_get_contents($backup_file)));
             
            $headers  = "From: ".$from.PHP_EOL;
            $headers .= "MIME-Version: 1.0".PHP_EOL;
            $headers .= "Content-Type: multipart/mixed; boundary=\"".$separator."\"";
             
            $body = "--".$separator.PHP_EOL;
            $body .= "Content-Type: application/octet-stream; name=\"".$filename."\"".PHP_EOL;
            $body .= "Content-Transfer-Encoding: base64".PHP_EOL;
            $body .= "Content-Disposition: attachment".PHP_EOL.PHP_EOL;
            $body .= $attachment.PHP_EOL;
            $body .= "--".$separator."--";
             
            if (mail($to, $subject, $body, $headers)) {
                unlink($backup_file);
            }

            echo 1;
        }
    }
}
