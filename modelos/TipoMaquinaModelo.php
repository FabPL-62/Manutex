<?php
class TipoMaquinaModelo extends Modelo {
    
    function __construct() {
        parent::__construct();
    }
    
    // consultar un tipo en especial
    function datos($codigo) 
    {
        return orm::find(orm::tipo_maquina([
            orm::tipo_maquina_codigo => $codigo
        ]),[
            "conexion" => $this->db,
            "objeto" => true
        ]);
    }
    
    // consultar si un tipo existe en la bd
    function existe($codigo) {
        
        // se consulta un tipo
        $resultado = $this->datos($codigo);
        
        // si no es nulo se retorna verdadero
        if ($resultado != null) return true;
        else return false;
        
    }

    // obtener todos los tipos
    function listar() 
    {    
        return orm::find(orm::tipo_maquina,[
            "conexion" => $this->db,
            "json" => true
        ]);
    }

    // obtener todos los tipos
    function listar_tipos() 
    {
        return orm::find(orm::tipo_maquina,[
            "conexion" => $this->db,
            "cabeceras" => false,
            "json" => true
        ]);
    }
    
    // agrega un nuevo tipo
    function agregar($tipo) {
        return orm::insert($tipo,$this->db);
    }

    // modificar los datos de un tipo
    function modificar($tipo) {
        return orm::update($tipo,$this->db);
    }

    // eliminar un tipo de maquina
    function eliminar($codigo)
    {
        return orm::delete(orm::tipo_maquina([
            orm::tipo_maquina_codigo => $codigo
        ]),$this->db);
    }

}
