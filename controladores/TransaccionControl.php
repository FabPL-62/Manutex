<?php
class TransaccionControl extends Controlador {
    
    function __construct() {
        parent::__construct();
    }

    // listar eventos
    function listar() {
        if (Controlador::input()) {
            echo $this->modelo->listar();
        }
    }

    // obtener ultima transaccion
    function ultima() {
        if (Controlador::input()) {
            echo $this->modelo->ultima();
        }
    }

    function agregar() {
        if (Controlador::input("fecha","monto","desc"))
        {
            $resultado = $this->modelo->agregar(orm::transaccion([
                orm::transaccion_fecha => $_POST["fecha"],
                orm::transaccion_monto => $_POST["monto"],
                orm::transaccion_descripcion => $_POST["desc"]
            ]));
            if ($resultado == true) {
                Controlador::success("La transacción ha sido ingresada exitosamente");
            } else {
                Controlador::error("Hubo un error al ingresar la transacción");
            }
        }
    }

    function modificar() {
        if (Controlador::input("fecha","num","monto","desc"))
        {
            $resultado = $this->modelo->modificar(orm::transaccion([
                orm::transaccion_numero => $_POST["num"],
                orm::transaccion_fecha => $_POST["fecha"],
                orm::transaccion_monto => $_POST["monto"],
                orm::transaccion_descripcion => $_POST["desc"]
            ]));
            if ($resultado == true) {
                Controlador::success("La transacción ha sido modificada exitosamente");
            } else {
                Controlador::error("Hubo un error al modificar la transacción");
            }
        }
    }

    function ingresos(){
        if (Controlador::input("año")) {
            echo $this->modelo->ingresos($_POST["año"]);
        }        
    }

    function egresos(){
        if (Controlador::input("año")) {
            echo $this->modelo->egresos($_POST["año"]);
        }
    }

    function cuadrar_caja(){
        if (Controlador::input("año")) {
            echo $this->modelo->cuadrar_caja($_POST["año"]);
        }
    }

    // agregar el valor de cuota anual
    function agregar_valor_anual() {
        if (Controlador::input("año","ingreso","mensual"))
        {
            // se manda al modelo a agregar un valor de cuota
            $resultado = $this->modelo->agregar_valor_anual(orm::valor_cuota([
                orm::valor_cuota_año => $_POST["año"],
                orm::valor_cuota_ingreso => $_POST["ingreso"],
                orm::valor_cuota_mensual => $_POST["mensual"]
            ]));
            if ($resultado == true) {
                Controlador::success("El valor anual se ha ingresado exitosamente");
            } else {
                Controlador::error("Hubo un error al ingresar el valor anual");
            }
        }
    }

    function modificar_valor_anual(){
        if (Controlador::input("año","ingreso","mensual"))
        {
            // se manda al modelo a agregar un valor de cuota
            $resultado = $this->modelo->modificar_valor_anual(orm::valor_cuota([
                orm::valor_cuota_año => $_POST["año"],
                orm::valor_cuota_ingreso => $_POST["ingreso"],
                orm::valor_cuota_mensual => $_POST["mensual"]
            ]));
            if ($resultado == true) {
                Controlador::success("El valor anual se ha modificado exitosamente");
            } else {
                Controlador::error("Hubo un error al modificar el valor anual");
            }
        }
    }

    function consultar_valor_anual(){
        if (Controlador::input("año")) {
            echo $this->modelo->consultar_valor_anual($_POST["año"]);
        }
    }

    function listar_cuotas(){
        if (Controlador::input("año")) {
            echo $this->modelo->listar_cuotas($_POST["año"]);
        }
    }

    function pagar_cuotas(){
        if (Controlador::input("año","fecha","meses","rut"))
        {
            echo $this->modelo->pagar_cuotas(orm::cuota([
                orm::cuota_año => $_POST["año"],
                orm::cuota_rut => $_POST["rut"],
                orm::cuota_fecha => $_POST["fecha"]
            ]),json_decode($_POST["meses"]));
        }   
    }

    function consultar_pagos(){
        if (Controlador::input("año","rut")) {
            echo $this->modelo->consultar_pagos($_POST["año"],$_POST["rut"]);
        }
    }
}