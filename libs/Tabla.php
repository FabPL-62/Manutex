<?php
require_once "ORM.php";
class tabla 
{
	private $tabla, $campos;
    public $alias;
	function __construct($tabla, $campos = null) 
    {
		$this->tabla  = $tabla;
		$this->campos = $campos;
        $this->alias  = "";

        $tablas = array_keys(orm::base);
        $definicion = null;
        if (isset($tablas[$this->tabla])) {
            $key = $tablas[$this->tabla];
            $definicion = orm::base[$key];
        }
        if ($definicion != null) {
            $this->alias = $definicion["alias"];
        }
	}
    function __get($campo)
    {
    	$tablas = array_keys(orm::base);
        $definicion = null;
        if (isset($tablas[$this->tabla])) {
            $key = $tablas[$this->tabla];
            $definicion = orm::base[$key];
        }
        if ($definicion != null)
        {
            switch ($campo)
            {
                case "id":
                    return $this->tabla;
                case "nombre":
                    return $key;
                break;
                case "definicion":
                case "cabeceras":
                case "primarias":
                    return $definicion[$campo]; 
                break;
                case "dependencias" : 
                    if (array_key_exists("dependencias", $definicion)) {
                        return $definicion["dependencias"]; 
                    } else {
                        return null;
                    }
                break;
                case "campos": return $this->campos; break;
                case "formatos":
                    if (array_key_exists("formateos", $definicion)) {
                        return $definicion["formateos"]; 
                    } else {
                        return null;
                    }
                break;
            }
        }
        return null;
            
    }
    function definir($campos) {
        $this->campos = $campos;
    }
    function get($id_campo) {

    	// obtenemos los campos
    	$campos = $this->campos;

    	if (isset($campos[$id_campo])) {
    		return $campos[$id_campo];
    	}
    	return null;
    }
    function set($id_campo, $valor) {

    	// obtenemos los campos
    	$campos = $this->definicion;

    	if (isset($campos[$id_campo])) {
    		$this->campos[$id_campo] = $valor;
    	}
    }
    function key($id_campo) 
    {
    	$tablas = array_keys(orm::base);
        $definicion = null;
        if (isset($tablas[$this->tabla])) {
            $key = $tablas[$this->tabla];
            $definicion = orm::base[$key];
        }
        if ($definicion != null)
        {
            $campos = $definicion["definicion"];
            if (isset($campos[$id_campo])) {
                if ($this->alias != "") return $this->alias.".".$campos[$id_campo];
                else return $campos[$id_campo];
            }
        }
    	return null;
    }
    function head($id_campo) 
    {
    	$tablas = array_keys(orm::base);
        $definicion = null;
        if (isset($tablas[$this->tabla])) {
            $key = $tablas[$this->tabla];
            $definicion = orm::base[$key];
        }
        if ($definicion != null)
        {
            $cabeceras = $definicion["cabeceras"];
        	if (isset($cabeceras[$id_campo])) {
        		return $cabeceras[$id_campo];
        	}
        }
    	return null;
    }
    function cond()
    {
        if ($this->campos != null) {
            $cond = [];
            foreach ($this->campos as $id_campo => $valor) {
        		$campo = $this->key($id_campo);
                if (is_string($valor)) {
                    if ($valor[0] == "(") {
                        $cond[] = "$campo = $valor";
                    } else {
                        $cond[] = "$campo = '$valor'";
                    }
                } else {
                    $cond[] = "$campo = '$valor'";
                }
            }
            $cond = implode(" and ",$cond);
            return $cond;
        }
        return "";
    }
}