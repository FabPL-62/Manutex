<?php
class MaquinaModelo extends Modelo {
    
    function __construct() {
        parent::__construct();
    }

    // obtener todas las maquinas
    function listar() {
        return orm::select([
            "tablas" => [orm::maquina,orm::tipo_maquina],
            "campos" => [
                orm::tipo_maquina_descripcion_def(),
                orm::maquina_all([
                    orm::maquina_codigo
                ])
            ],
            "orden" => [
                orm::tipo_maquina_descripcion() => "asc",
                orm::maquina_numero() => "asc"
            ],
            "salida" => [
                "conexion" => $this->db,
                "json" => true
            ]
        ]);
    }
    
    // agregar maquina
    function agregar(tabla $maquina)
    {
        // actualizamos la cantidad de maquinas del tipo
        orm::update(orm::tipo_maquina([
            orm::tipo_maquina_codigo => $maquina->get(orm::maquina_codigo),
            orm::tipo_maquina_cantidad => $maquina->get(orm::maquina_numero)
        ]),$this->db);

        // agregamos a la base de datos
        return orm::insert($maquina,$this->db);
    }

    // modificar maquina
    function modificar(tabla $maquina){
        return orm::update($maquina,$this->db);
    }

    // eliminar un tipo de maquina
    function eliminar(tabla $maquina)
    {
        // eliminamos la maquina de la base de datos
        $resultado = orm::delete($maquina,$this->db);

        // si se elimino, entonces tambien disminuye el contador en los tipos de maquina
        if (!is_array($resultado)) {
            if ($resultado == true) {
                return orm::update(orm::tipo_maquina([
                    orm::tipo_maquina_cantidad => "(".orm::tipo_maquina_cantidad()."-1)",
                    orm::tipo_maquina_codigo => $maquina->get(orm::maquina_codigo)
                ]),$this->db);
            }
        }
        return $resultado;
    }

    // consultar todas las maquinas disponibles para solicitar
    function consultar_maquinas()
    {
        return orm::select([
            "tablas" => [orm::maquina([
                orm::maquina_estado => "O"
            ]),orm::tipo_maquina],
            "campos" => [
                orm::maquina_codigo(),
                orm::tipo_maquina_descripcion(),
                orm::count(orm::maquina_codigo())
            ],
            "salida" => [
                "conexion" => $this->db,
                "cabeceras" => false,
                "json" => true
            ]
        ]);
    }

    // consultar las solicitudes hechas
    function consultar_solicitudes($rut) 
    {
        // se rechazan todas las pendientes, en donde la fecha actual es mayor
        $sql = "delete from solicitud_maquina 
                where rut_socia = '$rut' 
                  and fecha_solicitud < '".date("Y-m-d")."' 
                  and estado_solicitud != '3'
                  and estado_solicitud != '1'";
        $this->db->sql_execute($sql);
        return orm::select([
            "tablas" => orm::solicitud_maquina([
                orm::solicitud_maquina_rut => $rut
            ]),
            "campos" => [
                orm::solicitud_maquina_all([
                    orm::solicitud_maquina_rut
                ])
            ],
            "where" => orm::solicitud_maquina_estado()." < 2",
            "orden" => [
                orm::solicitud_maquina_fecha => "asc",
                orm::solicitud_maquina_turno => "asc"
            ],
            "salida" => [
                "conexion" => $this->db,
                "cabeceras" => false,
                "json" => true
            ]
        ]);
    }

    // agregar una solicitud a la bd
    function agregar_solicitud($solicitud)
    {
        // obtenemos algunos datos de la solicitud entrante
        $rut = $solicitud->get(orm::solicitud_maquina_rut);
        $tipo = $solicitud->get(orm::solicitud_maquina_codigo);
        $fecha = $solicitud->get(orm::solicitud_maquina_fecha);

        // obtenemos todas las solicitudes de ese dia
        $solicitudes = orm::find(
            orm::solicitud_maquina([
                orm::solicitud_maquina_fecha => $fecha,
                orm::solicitud_maquina_estado => 0
        ]),[
            orm::solicitud_maquina_turno => "asc",
            orm::solicitud_maquina_rut => "asc",
            orm::solicitud_maquina_codigo,
            orm::solicitud_maquina_numero
        ],[
            "conexion" => $this->db,
            "formato" => false,
            "objeto" => true
        ]);

        // obtenemos todas las maquinas disponibles
        $maquinas = orm::find(orm::maquina([
            orm::maquina_estado => "O",
            orm::maquina_codigo => $tipo
        ]),[
            orm::maquina_codigo => "asc",
            orm::maquina_numero
        ],[
            "conexion" => $this->db,
            "objeto" => true
        ]);

        // si hay solicitudes en ese dia
        $turno_disp = [];
        if ($solicitudes !== null) 
        {
            // obtenemos los turnos disponibles para la socia
            $_turno_ant = 0;
            $_turno_max = 0;
            foreach ($solicitudes as $sol) {
                $_turno = intval($sol->get(orm::solicitud_maquina_turno));
                $_rut = $sol->get(orm::solicitud_maquina_rut);
                if ($_rut == $rut) {
                    if ($_turno != $_turno_ant) {
                        if ($_turno - $_turno_ant > 1) {
                            for ($i = $_turno_ant + 1; $i < $_turno; $i++) {
                                $turno_disp[] = $i;
                            }
                        } $_turno_ant = $_turno;
                    }
                }
                if ($_turno > $_turno_max) {
                    $_turno_max = $_turno;
                }
            }
            $turno_disp[] = $_turno_ant+1;
            for ($i = $_turno_ant+2; $i <= $_turno_max+1; $i++) {
                $turno_disp[] = $i;
            }
        }
            
        // obtenemos el turno y el numero de maquina
        $turno = 1;
        $numero = 1;
        if (count($turno_disp) > 0) {
            foreach ($turno_disp as $_turno_act) {
                $_maquinas = $maquinas;
                foreach ($solicitudes as $sol) {
                    $_turno = intval($sol->get(orm::solicitud_maquina_turno));
                    $_tipo = $sol->get(orm::solicitud_maquina_codigo);
                    if ($_turno == $_turno_act) {
                        if ($_tipo == $tipo) {
                            $_numero = $sol->get(orm::solicitud_maquina_numero);
                            foreach ($_maquinas as $key => $maquina) {
                                $__tipo = $maquina->get(orm::maquina_codigo);
                                $__numero = $maquina->get(orm::maquina_numero);
                                if ($_tipo == $__tipo && $_numero == $__numero) {
                                    unset($_maquinas[$key]);
                                }
                            }
                        } else continue;
                    } else continue;
                }
                if (count($_maquinas) > 0) {
                    foreach ($_maquinas as $maquina) {
                        $turno = $_turno_act;
                        $numero = $maquina->get(orm::maquina_numero);
                        break;
                    }
                    break;
                } else continue;
            }
        }

        // asignamos el numero y el turno
        $solicitud->set(orm::solicitud_maquina_numero,$numero);
        $solicitud->set(orm::solicitud_maquina_turno,$turno);

        $resultado = orm::insert($solicitud,$this->db);
        if ($resultado == true) {
            return [$numero,$turno];
        }
        return 0;
    }

    // eliminar una solicitud
    function eliminar_solicitud($solicitud) {
        return orm::delete($solicitud,$this->db);
    }

    // listar para aceptar solicitudes
    function listar_aceptar() 
    {
        return orm::select([
            "tablas" => [orm::socia,orm::solicitud_maquina],
            "campos" => [
                "Fecha solicitud" => orm::solicitud_maquina_fecha(),
                "Cantidad" => orm::select([
                    "tablas" => orm::solicitud_maquina("sm2"),
                    "campos" => [orm::count("*")],
                    "where" => orm::equal(
                        orm::solicitud_maquina_fecha("sm2"),
                        orm::solicitud_maquina_fecha()
                    )
                ]),
                "Rut" => orm::socia_rut(),
                "Socia" => orm::concat(
                    orm::socia_nombre(),
                    orm::socia_paterno(),
                    orm::socia_materno()
                ),
                "Código" => orm::solicitud_maquina_codigo(),
                "Número" => orm::solicitud_maquina_numero(),
                "Estado" => orm::solicitud_maquina_estado(),
                "Turno" => orm::solicitud_maquina_turno()
            ],
            "where" => orm::opr(">=",[
                orm::solicitud_maquina_fecha(),
                "'".date("Y-m-d")."'"
            ]),
            "orden" => [
                "`Fecha Solicitud`" => "asc",
                "`Turno`" => "asc"
            ],
            "salida" => [
                "conexion" => $this->db,
                "grupo" => ["Fecha solicitud","Cantidad"],
                "formato" => [
                    "Rut" => true
                ],
                "json" => true
            ]
        ]);
    }

    function modificar_aceptar($tabla) 
    {
        foreach ($tabla as $fecha => $filas) 
        {
            // formateamos la fecha
            $fecha = implode("-",array_reverse(explode("/",$fecha)));

            // recorremos las filas que se editan
            if (count($filas) > 0)
            {
                foreach ($filas as $fila)
                {
                    // obtenemos los datos
                    $rut    = $fila[0];
                    $codigo = $fila[1];
                    $numero = $fila[2];
                    $turno  = $fila[3];
                    $estado = $fila[4];

                    $resultado = orm::update(orm::solicitud_maquina([
                        orm::solicitud_maquina_rut => $rut,
                        orm::solicitud_maquina_codigo => $codigo,
                        orm::solicitud_maquina_numero => $numero,
                        orm::solicitud_maquina_turno => $turno,
                        orm::solicitud_maquina_estado => $estado
                    ]),$this->db);
                    if ($resultado == false) {
                        return false;
                    }

                    // si el estado de solicitud es validar
                    if ($estado == "3") {

                        // aumentamos la cantidad de uso para la maquina
                        $sql = "update maquina set
                                    uso_maquina = uso_maquina + 1
                                where cod_tipo_maquina = '$codigo'
                                  and num_maquina = '$numero'";
                        // ejecutamos
                        $resultado = $this->db->sql_execute($sql);
                        if ($resultado == false) {
                            return false;
                        }
                    }
                }
            }
        }
        return $this->listar_aceptar();
    }
}
