<?php
class LoginModelo extends Modelo {
    
    function __construct() {
        parent::__construct();
    }
    
    // verificar rut y contraseña
    function login($rut, $contraseña) {

        $resultado = orm::select([
            "tablas" => orm::socia([
                orm::socia_rut => $rut,
                orm::socia_contraseña => $contraseña
            ]),
            "campos" => [
                orm::socia_nombre_def(),
                orm::socia_permiso_def()
            ],
            "where" => orm::socia_estado()." != 3",
            "salida" => [
                "conexion" => $this->db,
                "cabeceras" => false,
                "formato" => false
            ]
        ]);
        
        // el resultado no tiene que ser nulo
        if ($resultado != null) {

            $ejecucion = orm::update(
                orm::socia([
                    orm::socia_rut => $rut,
                    orm::socia_conectada => 1
                ]),
                $this->db
            );
            if ($ejecucion == true) {
                return $resultado[0];
            }
        }
        return null;
    }
}
