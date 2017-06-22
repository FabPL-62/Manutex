<?php
require "./libs/Rut.php";
class LoginControl extends Controlador {

    // se hereda el constructor
    function __construct() {
        parent::__construct();
    }
    
    // el login tiene un solo metodo, para saber si el login esta correcto        
    function login() 
    {
        if ($this->input("login_rut","login_pass"))
        {
            // obtenemos las entradas
            $rut = $_POST["login_rut"];
            $contraseña = $_POST["login_pass"];

            // obtenemos el cuerpo del rut, verificado
            $rut = rut_verificar($rut);

            // el rut no debe ser nulo
            if (is_numeric($rut))
            {
                // la contraseña no debe ser nula
                if (strlen($contraseña) > 0)
                {
                    // se manda al modelo a realizar el login
                    $resultado = $this->modelo->login($rut, $contraseña);

                    // si la consulta fue exitosa
                    if ($resultado != null) {
                        if (is_array($resultado)) {
                            Sesion::set_value("U_NAME",$rut);
                            Sesion::set_value("ID",$rut);
                            $this->success($resultado);
                        }
                    }
                    else $this->error("Usuario y/o Contraseña erronea");
                }
                else $this->error("ingrese contraseña");
            } 
            else $this->error($rut);
        }       
    }
}
