<?php
class EventoControl extends Controlador {
    
    function __construct() {
        parent::__construct();
    }
    
    // listar eventos
    function listar() {
        if ($this->input()) {
            echo $this->modelo->listar();
        }
    }

    // agregar un evento
    function agregar()
    {
        if ($this->input("evento_fecha","evento_tipo","evento_desc"))
        {
            $resultado = $this->modelo->agregar(orm::evento([
                orm::evento_fecha => $_POST["evento_fecha"],
                orm::evento_tipo => $_POST["evento_tipo"],
                orm::evento_descripcion => $_POST["evento_desc"]
            ]));
            if ($resultado === null) {
                echo "El evento ya existe en el sistema";
            } 
            else echo $resultado;
        }
    }

    // modificar evento
    function modificar()
    {
        if ($this->input("evento_fecha","evento_tipo","evento_desc"))
        {
            echo $this->modelo->modificar(orm::evento([
                orm::evento_fecha => $_POST["evento_fecha"],
                orm::evento_tipo => $_POST["evento_tipo"],
                orm::evento_descripcion => $_POST["evento_desc"]
            ]));
        }
    }
    
    // eliminar evento
    function eliminar()
    {
        if ($this->input("evento_fecha"))
        {
            $resultado = $this->modelo->eliminar($_POST["evento_fecha"]);
            if (is_array($resultado)) {
                echo $this->error("El evento que desea eliminar posee asistencias asociadas, no se puede eliminar");
            } else {
                if ($resultado == true) {
                   $this->success();
                } else $this->error("El evento no se pudo eliminar");
            }
        }
    }

    // consultar por eventos segun asistencia
    function consultar_eventos() {
        if ($this->input("estado")) {
            $estado = intval($_POST["estado"]);
            echo $this->modelo->consultar_eventos($estado);
        }
    }

    // consultar las socias por evento
    function consultar_socias() {
        if ($this->input("evento_fecha")) {
             echo $this->modelo->consultar_socias($_POST["evento_fecha"]);
        }
    }

    // consultar eventos por socia
    function consultar_eventos_socia() {
        if ($this->input("rut","año")) {
            $rut = explode("-",$_POST["rut"])[0];
            $año = $_POST["año"];
            $resultado = $this->modelo->consultar_eventos_socia($rut,$año);
            if (count($resultado) > 0) {
                $this->success($resultado);
            } else {
                $this->error("No hay asistencias en el año ".$_POST["año"]);
            }
        }
    }

    // consultar total anual por socia
    function consultar_eventos_anual() {
        if ($this->input("año")) {
            $resultado = $this->modelo->consultar_porcentajes($_POST["año"]);
            if ($resultado != null) {
                $this->success($resultado);
            } else {
                $this->error("No hay asistencias en el año ".$_POST["año"]);
            }
        }
    }

    // agregar asistencia a un evento
    function agregar_asistencia() {
        if ($this->input("asistencia","fecha")) {

            // obtenemos el arreglo de asistencia
            $asistencia = json_decode($_POST["asistencia"]);
            $fecha = $_POST["fecha"];

            // llamamos al modelo para agregar
            $resultado = $this->modelo->agregar_asistencia($fecha,$asistencia);
            if ($resultado == true) {
                $this->success("La asistencia se ingreso exitosamente al evento");
            } else {
                $this->error("Hubo un error al ingresar la asistencia al evento");
            }
        }
    }

    // modificar asistencia
    function modificar_asistencia() {
        if ($this->input("asistencia","fecha")) {

            // obtenemos el arreglo de asistencia
            $asistencia = json_decode($_POST["asistencia"]);
            $fecha = $_POST["fecha"];

            // llamamos al modelo para agregar
            $resultado = $this->modelo->modificar_asistencia($fecha,$asistencia);
            if ($resultado == true) {
                $this->success("La asistencia se modificó exitosamente");
            } else {
                $this->error("Hubo un error al modificar la asistencia");
            }
        }
    }
} 