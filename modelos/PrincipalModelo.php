<?php
class PrincipalModelo extends Modelo {
    
    function __construct() {
        parent::__construct();
    }

    function salir($rut)
    {
    	return orm::update(orm::socia([
            orm::socia_rut => $rut,
            orm::socia_conectada => 0
        ]),$this->db);
    }
}
