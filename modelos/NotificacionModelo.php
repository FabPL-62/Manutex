<?php
class NotificacionModelo extends Modelo {
    
    function __construct() {
        parent::__construct();
    }
    
    //consultar por las notificcaciones de una socia
    function consultar_notificaciones($rut)
    {
        return orm::select([
            "tablas" => [orm::notificacion,orm::socia],
            "campos" => [
                orm::notificacion_all(),
                orm::socia_permiso_def()
            ],
            "where" => orm::opr("in",[
                orm::notificacion_codigo(),
                orm::select([
                    "tablas" => orm::notificacion_destinatario([
                        orm::notificacion_destinatario_rut => $rut
                    ]),
                    "campos" => [
                        orm::notificacion_destinatario_codigo
                    ]
                ])
            ]),
            "orden" => [
                orm::notificacion_fecha() => "desc"
            ],
            "salida" => [
                "conexion" => $this->db,
                "cabeceras" => false,
                "formato" => [
                    orm::notificacion_fecha_head() => true,
                    orm::socia_permiso_head() => true
                ],
                "json" => true
            ]
        ]);
    }

    function consultar_notificaciones_enviadas($rut) {
        return orm::find(orm::notificacion([
            orm::notificacion_remitente => $rut
        ]),[
            "conexion" => $this->db,
            "cabeceras" => false,
            "json" => true
        ]);
    } 

    function consultar_destinatarios($rut,$cod) 
    {
        $resultado = orm::select([
            "tablas" => orm::socia(),
            "campos" => [
                "Rut" => orm::socia_rut(),
                "Nombre Completo" => orm::concat(
                    orm::socia_nombre(),
                    orm::socia_paterno(),
                    orm::socia_materno()
                ),
                "Asociado" => orm::select([
                    "tablas" => orm::notificacion_destinatario(),
                    "campos" => [
                        orm::count("*")
                    ],
                    "where" => orm::and(
                        orm::equal(orm::notificacion_destinatario_rut(),orm::socia_rut()),
                        orm::equav(orm::notificacion_destinatario_codigo(),$cod)
                    )
                ])
            ],
            "where" => orm::and(
                orm::socia_estado()." != '3'",
                orm::socia_rut()." != '$rut'"
            ),
            "orden" => [
                orm::socia_paterno() => "asc"
            ],
            "salida" => [
                "conexion" => $this->db
            ]
        ]);
        unset($resultado[0][2]);
        return json_encode($resultado);
    }

    function listar_respuesta($cod) {
        return orm::select([
            "tablas" => [orm::notificacion_respuesta([
                orm::notificacion_respuesta_codigo => $cod
            ]), orm::socia],
            "campos" => [
                orm::notificacion_respuesta_all(),
                orm::socia_nombre_def()
            ],
            "orden" => [
                orm::notificacion_respuesta_fecha() => "asc"
            ],
            "salida" => [
                "conexion" => $this->db,
                "cabeceras" => false,
                "formato" => [
                    orm::notificacion_respuesta_fecha_head() => true
                ],
                "json" => true
            ]
        ]);
    }

    function agregar_respuesta($respuesta){
        $max = 1+orm::select([
            "tablas" => orm::notificacion_respuesta([
                orm::notificacion_respuesta_codigo => $respuesta->get(orm::notificacion_respuesta_codigo)
            ]),
            "campos" => [
                orm::ifnull(orm::max(orm::notificacion_respuesta_numero()),0)
            ],
            "salida" => [
                "conexion" => $this->db,
                "cabeceras" => false
            ]
        ])[0][0];
        $respuesta->set(orm::notificacion_respuesta_numero,$max);
        return orm::insert($respuesta,$this->db)*$max;
    }

    function editar_respuesta($respuesta){
        return orm::update($respuesta,$this->db);
    }

    function eliminar_respuesta($cod_not,$cod_resp){
        return orm::delete(orm::notificacion_respuesta([
            orm::notificacion_respuesta_codigo => $cod_not,
            orm::notificacion_respuesta_numero => $cod_resp
        ]),$this->db);
    }   
    
    function agregar_notificacion($notificacion,$destinatarios) {
        $max = orm::select([
            "tablas" => orm::notificacion,
            "campos" => [
                orm::ifnull(orm::max(orm::notificacion_codigo())."+1",0)
            ],
            "salida" => [
                "conexion" => $this->db,
                "cabeceras" => false
            ]
        ])[0][0];
        $notificacion->set(orm::notificacion_codigo,$max);
        $not = orm::insert($notificacion,$this->db);
        $resultado = true;
        foreach ($destinatarios as $registro) {
            $resultado = ($resultado && orm::insert(orm::notificacion_destinatario([
                orm::notificacion_destinatario_codigo => $max,
                orm::notificacion_destinatario_rut => $registro
            ]),$this->db));
        }
        return $resultado;
    }

    function modificar_notificacion($notificacion,$destinatarios)
    {
        // se actualiza la notificacion
        orm::update($notificacion,$this->db);
        $codigo = $notificacion->get(orm::notificacion_codigo);

        // borramos todas las intersecciones
        orm::delete(orm::notificacion_destinatario([
            orm::notificacion_destinatario_codigo => $codigo
        ]),$this->db);

        $resultado = true;
        foreach ($destinatarios as $registro)
        {
            $resultado = ($resultado && orm::insert(orm::notificacion_destinatario([
                orm::notificacion_destinatario_codigo => $codigo,
                orm::notificacion_destinatario_rut => $registro
            ]),$this->db));
        }
        if ($resultado == true) return 1;
        else return "Error al modificar la notificacion";
    }

    function eliminar_notificacion($codigo){
        $consulta = orm::delete(orm::notificacion_respuesta([
            orm::notificacion_respuesta_codigo => $codigo
        ]),$this->db);
        if ($consulta === true) {
            $consulta = orm::delete(orm::notificacion_destinatario([
                orm::notificacion_destinatario_codigo => $codigo
            ]),$this->db);
            if ($consulta === true) {
                $consulta = orm::delete(orm::notificacion([
                    orm::notificacion_codigo => $codigo
                ]),$this->db);
                return $consulta;
            }
        }
    }

}