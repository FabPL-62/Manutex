<?php
class TipoMaquinaControl extends Controlador {
    
    function __construct() {
        parent::__construct();
    }
    
    // listar tipos de maquina
    function listar() {
        if ($this->input()) {
            echo $this->modelo->listar();
        }
    }

    // obtener tipos de maquina
    function listar_tipos() {
        if ($this->input()) {
            echo $this->modelo->listar_tipos();
        }
    }

    // agregar un tipo de maquina
    function agregar()
    {
        if ($this->input("tipo_codigo","tipo_descripcion","tipo_cantidad"))
        {
            $resultado = $this->modelo->agregar(orm::tipo_maquina([
                orm::tipo_maquina_codigo => $_POST["tipo_codigo"],
                orm::tipo_maquina_descripcion => $_POST["tipo_descripcion"],
                orm::tipo_maquina_cantidad => $_POST["tipo_cantidad"]
            ]));
            if ($resultado !== null) {
                if ($resultado == true) {
                    $this->success("El tipo de máquina ha sido ingresado exitosamente");
                } else {
                    $this->error("Hubo un error al ingresar el tipo de máquina");
                }
            } else {
                $this->error("El tipo de máquina ya existe en el sistema");
            }
        }
    }

    // modificar una socia
    function modificar()
    {
        if ($this->input("tipo_codigo","tipo_descripcion","tipo_cantidad"))
        {
            $resultado = $this->modelo->modificar(orm::tipo_maquina([
                orm::tipo_maquina_codigo => $_POST["tipo_codigo"],
                orm::tipo_maquina_descripcion => $_POST["tipo_descripcion"],
                orm::tipo_maquina_cantidad => $_POST["tipo_cantidad"]
            ]));
            if ($resultado == true) {
                $this->success("El tipo de máquina ha sido modificado exitosamente");
            } else {
                $this->error("Hubo un error al modificar el tipo de máquina");
            }
        }
    }
    
    // eliminar a una socia
    function eliminar()
    {
        if ($this->input("tipo_codigo"))
        {
            $resultado = $this->modelo->eliminar($_POST["tipo_codigo"]);
            if (is_array($resultado)) {
                $this->error("El tipo de máquina tiene máquinas asociadas, no se puede eliminar");
            } else {
                if ($resultado == true) {
                    $this->success("El tipo de máquina ha sido eliminado exitosamente");
                } else {
                    $this->error("Error al eliminar tipo de maquina");
                }
            }
        }
    }
} 