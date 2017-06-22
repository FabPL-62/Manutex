<?php
class MaquinaControl extends Controlador {
    
    function __construct() {
        parent::__construct();
    }
    
    // listar maquinas
    function listar() {
        if ($this->input()) {
            echo $this->modelo->listar();
        }
    }

    // agregar maquina
    function agregar()
    {
        if ($this->input(
            "maq_tipo",
            "maq_modelo",
            "maq_estado",
            "maq_num",
            "maq_marca",
            "maq_ingreso",
            "maq_mantencion"
        )){
            $resultado = $this->modelo->agregar(orm::maquina([
                orm::maquina_codigo => $_POST["maq_tipo"],
                orm::maquina_modelo => $_POST["maq_modelo"],
                orm::maquina_estado => $_POST["maq_estado"],
                orm::maquina_numero => $_POST["maq_num"],
                orm::maquina_marca  => $_POST["maq_marca"],
                orm::maquina_ingreso => $_POST["maq_ingreso"],
                orm::maquina_mantencion => $_POST["maq_mantencion"]
            ]));

            // el resultado puede ser 1 o 0
            if ($resultado == true) {
                $this->success("La máquina se ingreso exitosamente en el sistema");
            } else {
                $this->error("Error al ingresar máquina");
            }
        }
    }

    // modificar maquina
    function modificar()
    {
        if ($this->input(
            "maq_tipo",
            "maq_modelo",
            "maq_estado",
            "maq_num",
            "maq_marca",
            "maq_ingreso",
            "maq_mantencion"
        )){
            $resultado = $this->modelo->modificar(orm::maquina([
                orm::maquina_codigo => $_POST["maq_tipo"],
                orm::maquina_modelo => $_POST["maq_modelo"],
                orm::maquina_estado => $_POST["maq_estado"],
                orm::maquina_numero => $_POST["maq_num"],
                orm::maquina_marca  => $_POST["maq_marca"],
                orm::maquina_ingreso => $_POST["maq_ingreso"],
                orm::maquina_mantencion => $_POST["maq_mantencion"]
            ]));

            // el resultado puede ser 1 o 0
            if ($resultado == true) {
                $this->success("La máquina se modificó exitosamente en el sistema");
            } else {
                $this->error("Error al modificar máquina");
            }
        }
    }
    
    // eliminar maquina
    function eliminar()
    {
        if ($this->input("maq_tipo","maq_num"))
        {
            $resultado = $this->modelo->eliminar(orm::maquina([
                orm::maquina_codigo => $_POST["maq_tipo"],
                orm::maquina_numero => $_POST["maq_num"]
            ]));
            if (is_array($resultado)) {
                $this->error("La máquina que desea eliminar posee solicitudes asociadas, no se puede eliminar");
            } else {
                if ($resultado == true) {
                    $this->success();
                } else {
                    $this->error("La máquina no se pudo eliminar");
                }
            }
        }
    }

    // consultar por todas las maquinas disponibles
    function consultar_maquinas() {
        if ($this->input("fecha_solicitud")) {
            echo $this->modelo->consultar_maquinas();
        }
    }

    // consultar por todas las solicitudes de una socia
    function consultar_solicitudes() {
        if ($this->input("rut")) {
            $rut = explode("-",$_POST["rut"])[0];
            echo $this->modelo->consultar_solicitudes($rut);
        }
    }

    // agregar una solicitud de maquina
    function agregar_solicitud() 
    {
        if ($this->input("sol_rut","sol_cod","sol_fecha")) 
        {
            $resultado = $this->modelo->agregar_solicitud(orm::solicitud_maquina([
                orm::solicitud_maquina_rut => $_POST["sol_rut"],
                orm::solicitud_maquina_codigo => $_POST["sol_cod"],
                orm::solicitud_maquina_fecha => $_POST["sol_fecha"]
            ]));
            if ($resultado != null) {
                if (is_array($resultado)) {
                    $this->success($resultado);
                }
            } else {
                $this->error("No se pudo solicitar la maquina");
            }
        }
    }

    // eliminar una solicitud de maquina
    function eliminar_solicitud() 
    {
        if ($this->input(
            "sol_rut",
            "sol_cod",
            "sol_num",
            "sol_fecha",
            "sol_turno"
        )){
            $resultado = $this->modelo->eliminar_solicitud(orm::solicitud_maquina([
                orm::solicitud_maquina_rut => $_POST["sol_rut"],
                orm::solicitud_maquina_codigo => $_POST["sol_cod"],
                orm::solicitud_maquina_numero => $_POST["sol_num"],
                orm::solicitud_maquina_fecha => $_POST["sol_fecha"],
                orm::solicitud_maquina_turno  => $_POST["sol_turno"]
            ]));

            // el resultado puede ser 1 o 0
            if ($resultado == true) {
                $this->success("La eliminación de la solicitud ha sido exitosa");
            } else {
                $this->error("Error al eliminar solicitud de máquina");
            }
        }
    }

    // listar para aceptar solicitudes
    function listar_aceptar() {
        if ($this->input()) {
            echo $this->modelo->listar_aceptar();
        }
    }

    // modificar estado de las solicitudes
    function modificar_aceptar() {
        if ($this->input("json")) {
            $tabla = json_decode($_POST["json"]);
            echo $this->modelo->modificar_aceptar($tabla);
        }
    }
} 