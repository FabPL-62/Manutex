<?php
class EventoControl extends Controlador {
    
    function __construct() {
        parent::__construct();
    }
    
    // listar eventos
    function listar() {
        if (Controlador::input()) {
            echo $this->modelo->listar();
        }
    }

    // agregar un evento
    function agregar()
    {
        if (Controlador::input("evento_fecha","evento_tipo","evento_desc"))
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
        if (Controlador::input("evento_fecha","evento_tipo","evento_desc"))
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
        if (Controlador::input("evento_fecha"))
        {
            $resultado = $this->modelo->eliminar($_POST["evento_fecha"]);
            if (is_array($resultado)) {
                echo $this->error("El evento que desea eliminar posee asistencias asociadas, no se puede eliminar");
            } else {
                if ($resultado == true) {
                   Controlador::success();
                } else Controlador::error("El evento no se pudo eliminar");
            }
        }
    }

    // consultar por eventos segun asistencia
    function consultar_eventos() {
        if (Controlador::input("estado")) {
            $estado = intval($_POST["estado"]);
            echo $this->modelo->consultar_eventos($estado);
        }
    }

    // consultar las socias por evento
    function consultar_socias() {
        if (Controlador::input("evento_fecha")) {
             echo $this->modelo->consultar_socias($_POST["evento_fecha"]);
        }
    }

    // consultar eventos por socia
    function consultar_eventos_socia() {
        if (Controlador::input("rut","año")) {
            $rut = explode("-",$_POST["rut"])[0];
            $año = $_POST["año"];
            $resultado = $this->modelo->consultar_eventos_socia($rut,$año);
            if (count($resultado) > 0) {
                Controlador::success($resultado);
            } else {
                Controlador::error("No hay asistencias en el año ".$_POST["año"]);
            }
        }
    }

    // consultar total anual por socia
    function consultar_eventos_anual() {
        if (Controlador::input("año")) {
            $resultado = $this->modelo->consultar_porcentajes($_POST["año"]);
            if ($resultado != null) {
                Controlador::success($resultado);
            } else {
                Controlador::error("No hay asistencias en el año ".$_POST["año"]);
            }
        }
    }

    // agregar asistencia a un evento
    function agregar_asistencia() {
        if (Controlador::input("asistencia","fecha")) {

            // obtenemos el arreglo de asistencia
            $asistencia = json_decode($_POST["asistencia"]);
            $fecha = $_POST["fecha"];

            // llamamos al modelo para agregar
            $resultado = $this->modelo->agregar_asistencia($fecha,$asistencia);
            if ($resultado == true) {
                Controlador::success("La asistencia se ingreso exitosamente al evento");
            } else {
                Controlador::error("Hubo un error al ingresar la asistencia al evento");
            }
        }
    }

    // modificar asistencia
    function modificar_asistencia() {
        if (Controlador::input("asistencia","fecha")) {

            // obtenemos el arreglo de asistencia
            $asistencia = json_decode($_POST["asistencia"]);
            $fecha = $_POST["fecha"];

            // llamamos al modelo para agregar
            $resultado = $this->modelo->modificar_asistencia($fecha,$asistencia);
            if ($resultado == true) {
                Controlador::success("La asistencia se modificó exitosamente");
            } else {
                Controlador::error("Hubo un error al modificar la asistencia");
            }
        }
    }
} 