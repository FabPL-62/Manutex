<?php
class NotificacionControl extends Controlador {
    
    function __construct(){
        parent::__construct();
    }
       
    function consultar_notificaciones() {
        if (Controlador::input("rut")) {
            $rut = $_POST["rut"];   
            echo $this->modelo->consultar_notificaciones($rut);
        }
    } 

    function consultar_notificaciones_enviadas() {
        if (Controlador::input("rut")) {
            echo $this->modelo->consultar_notificaciones_enviadas($_POST["rut"]);
        } 
    } 

    function consultar_destinatarios() {
        if (Controlador::input("codigo","rut")) {
            $rut = $_POST["rut"];
            $cod = $_POST["codigo"];
            echo $this->modelo->consultar_destinatarios($rut,$cod);
        }  
    }

    function listar_respuesta() {
        if (Controlador::input("codigo")) {
            $cod = $_POST["codigo"];
            echo $this->modelo->listar_respuesta($cod);
        }
    }

    function agregar_respuesta() {
        if (Controlador::input("cod_not","rut","descripcion","fecha")) {
            $resultado = $this->modelo->agregar_respuesta(orm::notificacion_respuesta([
                orm::notificacion_respuesta_codigo => $_POST["cod_not"],
                orm::notificacion_respuesta_fecha => $_POST["fecha"],
                orm::notificacion_respuesta_rut => $_POST["rut"],
                orm::notificacion_respuesta_descripcion => $_POST["descripcion"]
            ]));
            if ($resultado >= 0) {
                echo json_encode([$resultado,"Su respuesta ha sido enviada exitosamente"]);
            } else {
                echo json_encode([-1,"Su respuesta no se pudo enviar"]);
            }
        }
    }

    function editar_respuesta() {
        if (Controlador::input("cod_not","descripcion","cod_resp")) {
            $resultado = $this->modelo->editar_respuesta(orm::notificacion_respuesta([
                orm::notificacion_respuesta_codigo => $_POST["cod_not"],
                orm::notificacion_respuesta_descripcion => $_POST["descripcion"],
                orm::notificacion_respuesta_numero => $_POST["cod_resp"]
            ]));
            if ($resultado == true) {
                Controlador::success("La respuesta ha sido editada exitosamente");
            } else {
                Controlador::error("La respuesta no se pudo editar");
            }
        }
    }

    function eliminar_respuesta() {
        if (Controlador::input("cod_not","cod_resp")) {
            echo $this->modelo->eliminar_respuesta($_POST["cod_not"],$_POST["cod_resp"]);
        }  
    }   
    
    function agregar_notificacion() {
        if (Controlador::input(
            "asunto",
            "contenido",
            "destinatarios",
            "fecha",
            "rut"
        )){
            $destinatarios = json_decode($_POST["destinatarios"]); 
            $destinatarios[] = $_POST["rut"];
            $resultado = $this->modelo->agregar_notificacion(orm::notificacion([
                orm::notificacion_fecha => $_POST["fecha"],
                orm::notificacion_remitente => $_POST["rut"],
                orm::notificacion_asunto => $_POST["asunto"],
                orm::notificacion_descripcion => $_POST["contenido"]
            ]),$destinatarios);
            if ($resultado == true) {
                Controlador::success("La notificación ha sido enviada exitosamente");
            } else {
                Controlador::error("Hubo un error al ingresar la notificación");
            }
        } 
    }

    function modificar_notificacion() {
        if (Controlador::input(
            "asunto",
            "contenido",
            "destinatarios",
            "codigo",
            "rut"
        )){
            $destinatarios = json_decode($_POST["destinatarios"]); 
            $destinatarios[] = $_POST["rut"];
            $resultado = $this->modelo->modificar_notificacion(orm::notificacion([
                orm::notificacion_codigo => $_POST["codigo"],
                orm::notificacion_asunto => $_POST["asunto"],
                orm::notificacion_descripcion => $_POST["contenido"]
            ]),$destinatarios);
            if ($resultado == true) {
                Controlador::success("La notificación ha sido modificada exitosamente");
            } else {
                Controlador::error("Hubo un error al modificar la notificación");
            }
        }
    }

    function eliminar_notificacion(){
        if (Controlador::input("cod")){
            $resultado = $this->modelo->eliminar_notificacion($_POST["cod"]);
            if ($resultado == true) {
                Controlador::success("La notificación se eliminó exitosamente del sistema");
            } else {
                Controlador::error("Hubo un error al eliminar la notificación");
            }
        }    
    }
}