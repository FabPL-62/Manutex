<?php
class EventoModelo extends Modelo {
    
    function __construct() {
        parent::__construct();
    }
    
    // consultar si un evento existe en la bd
    function existe($fecha) 
    {
        $resultado = orm::find(
            orm::evento([
                orm::evento_fecha => $fecha
            ]),
            $this->db
        );
        if (count($resultado) > 1) return true;
        else return false;
    }

    // listar todos los eventos
    function listar() 
    {
        return json_encode(orm::find(
            orm::evento(),[
                orm::evento_fecha => "desc",
                orm::evento_tipo => "asc",
                orm::evento_descripcion
            ],
            $this->db
        ));
    }

    // agrega un nuevo evento
    function agregar($evento) {
        return orm::insert($evento,$this->db);
    }

    // modificar los datos de un evento
    function modificar($evento) {
        return orm::update($evento,$this->db);
    }

    // eliminar un evento
    function eliminar($fecha)
    {
        return orm::delete(
            orm::evento([
                orm::evento_fecha => $fecha
            ]),
            $this->db
        );
    }

    // se consulta una lista de eventos para la asistencia
    function consultar_eventos($estado) {

        if ($estado == 1) $opr = " not in ";
        else $opr = " in ";

        return json_encode(orm::select([
            "tablas" => orm::evento,
            "campos" => [
                orm::evento_tipo,
                orm::evento_fecha
            ],
            "where" => orm::opr($opr,[
                orm::evento_fecha(),
                orm::select([
                    "tablas" => orm::asistencia,
                    "campos" => [
                        "distinct ".orm::asistencia_fecha()
                ]])
            ]),
            "orden" => [
                orm::evento_tipo => "asc",
                orm::evento_fecha => "asc"
            ],
            "salida" => [
                "conexion" => $this->db,
                "cabeceras" => false,
                "formato" => [
                    orm::evento_fecha_head() => true
                ]
            ]
        ]));
    }

    // consultar socias por evento
    function consultar_socias($fecha)
    {
        return json_encode(orm::select([
            "tablas" => [
                orm::socia(),
                orm::asistencia([
                    orm::asistencia_fecha => $fecha
                ])
            ],
            "campos" => [
                orm::socia_rut_def(),
                orm::socia_nombre_def(),
                orm::socia_paterno_def(),
                orm::socia_materno_def(),
                "Asistencia" => orm::asistencia_asistio()
            ],
            "orden" => [
                orm::socia_rut() => "asc"
            ],
            "salida" => [
                "conexion" => $this->db,
                "cabeceras" => false
            ]
        ]));
    }

    // consultar por todos los eventos, y ver asistencia por una socia
    function consultar_eventos_socia($rut,$año) 
    {
        return orm::select([
            "tablas" => [
                orm::evento(),
                orm::asistencia([
                    orm::asistencia_rut => $rut
                ])
            ],
            "campos" => [
                "Tipo Evento" => orm::evento_tipo(),
                "Fecha" => orm::asistencia_fecha(),
                "Asistencia" => orm::asistencia_asistio()
            ],
            "where" => orm::like(orm::asistencia_fecha(),$año."%"),
            "orden" => [
                orm::asistencia_fecha() => "asc"
            ],
            "salida" => [
                "conexion" => $this->db,
                "cabeceras" => false
            ]
        ]);
    }

    // agregar asistencia a un evento
    function agregar_asistencia($fecha, $asistencia) 
    {
        // recorremos el arreglo de asistencia
        $resultado = true;
        foreach ($asistencia as $registro)
        {
            if ($registro[1]) $registro[1] = '1';
            else $registro[1] = '0';
            $resultado = ($resultado && orm::insert(
                orm::asistencia([
                    orm::asistencia_fecha => $fecha,
                    orm::asistencia_rut => $registro[0],
                    orm::asistencia_asistio => $registro[1]
                ]),
                $this->db
            ));
        }
        return $resultado;
    }

    // modificar asistencia
    function modificar_asistencia($fecha, $asistencia) 
    {
        // recorremos el arreglo de asistencia
        $resultado = true;
        foreach ($asistencia as $registro)
        {
            if ($registro[1]) $registro[1] = '1';
            else $registro[1] = '0';
            $resultado = ($resultado && orm::update(
                orm::asistencia([
                    orm::asistencia_fecha => $fecha,
                    orm::asistencia_rut => $registro[0],
                    orm::asistencia_asistio => $registro[1]
                ]),
                $this->db
            ));
        }
        return $resultado;
    }

    function consultar_porcentajes($año)
    {
        return orm::select([
            "tablas" => [orm::socia(),orm::asistencia()],
            "campos" => [
                orm::socia_rut_def(),
                orm::socia_nombre_def(),
                orm::socia_paterno_def(),
                orm::socia_materno_def(),
                "Porcentaje" => orm::round(orm::avg(orm::asistencia_asistio())."*100"),
                "Asistidos" => orm::sum(orm::asistencia_asistio()),
                "Total" => orm::count(orm::asistencia_asistio())
            ],
            "where" => orm::like(orm::asistencia_fecha(),$año."%"),
            "salida" => [
                "conexion" => $this->db,
                "cabeceras" => false,
                "formato" => [
                    "Porcentaje" => function($entrada) {
                        return $entrada."%";
                    },
                    orm::socia_rut_head() => true
                ]
            ]
        ]);      
    }
}