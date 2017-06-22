<?php
require_once "Rut.php";
require_once "Tabla.php";
require_once "Conexion.php";
class orm
{
    // -------------------------------------------------------------------------------------
    // DEFINICION BASE
    // -------------------------------------------------------------------------------------
    const base = [
        "socia" => [
            "alias" => "s",
            "definicion" => [
                "rut_socia",
                "nom_socia",
                "apP_socia",
                "apM_socia",
                "fono_socia",
                "dir_socia",
                "email_socia",
                "estado_socia",
                "cod_permiso",
                "contraseña",
                "fecha_ingreso",
                "fecha_eliminacion",
                "conectada"
            ],
            "cabeceras" => [
                "Rut",
                "Nombre",
                "Ap. Paterno",
                "Ap. Materno",
                "Fono",
                "Dirección",
                "Email",
                "Estado",
                "Permiso",
                "Contraseña",
                "Fecha de Ingreso",
                "Fecha de eliminación",
                "Activa"
            ],
            "formateos" => [
                self::socia_rut => "fmt_socia_rut",
                self::socia_estado => "fmt_socia_estado",
                self::socia_permiso => "fmt_socia_permiso",
                self::socia_ingreso => "fmt_fecha",
                self::socia_eliminacion => "fmt_fecha"
            ],
            "primarias" => [self::socia_rut],
            "dependencias" => [
                self::socia_rut => [
                    self::cuota => self::cuota_rut,
                    self::asistencia => self::asistencia_rut,
                    self::notificacion_destinatario => self::notificacion_destinatario_rut,
                    self::notificacion_respuesta => self::notificacion_respuesta_rut,
                    self::notificacion => self::notificacion_remitente,
                    self::solicitud_maquina => self::solicitud_maquina_rut
                ]
            ]
        ],
        "asistencia" => [
            "alias" => "a",
            "definicion" => [
                "fecha_evento",
                "rut_socia",
                "asistio"
            ],
            "cabeceras" => [
                "Fecha de evento",
                "Rut socia",
                "Asistencia"
            ],
            "formateos" => [
                self::asistencia_fecha => "fmt_fecha_tiempo",
                self::asistencia_rut => "fmt_socia_rut"
            ],
            "primarias" => [
                self::asistencia_fecha,
                self::asistencia_rut
            ]
        ],
        "evento" => [
            "alias" => "e",
            "definicion" => [
                "fecha_evento",
                "tipo_evento",
                "descripcion"
            ],
            "cabeceras" => [
                "Fecha de evento",
                "Tipo de evento",
                "Descripción"
            ],
            "formateos" => [
                self::evento_fecha => "fmt_fecha_tiempo",
                self::evento_tipo => "fmt_evento_tipo"
            ],
            "primarias" => [self::evento_fecha],
            "dependencias" => [
                self::evento_fecha => [
                    self::asistencia => self::asistencia_fecha
                ]
            ]
        ],
        "solicitud_maquina" => [
            "alias" => "sm",
            "definicion" => [
                "rut_socia",
                "cod_tipo_maquina",
                "num_maquina",
                "fecha_solicitud",
                "estado_solicitud",
                "turno_solicitud"
            ],
            "cabeceras" => [
                "Rut socia",
                "Código",
                "Número",
                "Fecha de solicitud",
                "Estado",
                "Turno"
            ],
            "formateos" => [
                self::solicitud_maquina_rut => "fmt_socia_rut",
                self::solicitud_maquina_fecha => "fmt_fecha",
                self::solicitud_maquina_estado => "fmt_solicitud_maquina_estado"
            ],
            "primarias" => [
                self::solicitud_maquina_rut,
                self::solicitud_maquina_codigo,
                self::solicitud_maquina_numero,
                self::solicitud_maquina_fecha,
                self::solicitud_maquina_turno
            ]
        ],
        "maquina" => [
            "alias" => "m",
            "definicion" => [
                "cod_tipo_maquina",
                "num_maquina",
                "marca_maquina",
                "modelo_maquina",
                "estado_maquina",
                "fecha_ingreso",
                "fecha_mantencion",
                "uso_maquina"
            ],
            "cabeceras" => [
                "Código",
                "Número",
                "Marca",
                "Modelo",
                "Estado",
                "Fecha de Ingreso",
                "Fecha de Mantención",
                "Cantidad de Uso"
            ],
            "formateos" => [
                self::maquina_estado => "fmt_maquina_estado",
                self::maquina_ingreso => "fmt_fecha",
                self::maquina_mantencion => "fmt_fecha"
            ],
            "primarias" => [
                self::maquina_codigo,
                self::maquina_numero
            ],
            "dependencias" => [
                self::maquina_codigo => [
                    self::solicitud_maquina => self::solicitud_maquina_codigo
                ],
                self::maquina_numero => [
                    self::solicitud_maquina => self::solicitud_maquina_numero
                ]
            ]
        ],
        "tipo_maquina" => [
            "alias" => "tm",
            "definicion" => [
                "cod_tipo_maquina",
                "descripcion_maquina",
                "cantidad_maquina"
            ],
            "cabeceras" => [
                "Código",
                "Descripción",
                "Cantidad"
            ],
            "primarias" => [self::tipo_maquina_codigo],
            "dependencias" => [
                self::tipo_maquina_codigo => [
                    self::maquina => self::maquina_codigo
                ]
            ]
        ],
        "notificacion_destinatario" => [
            "alias" => "nd",
            "definicion" => [
                "cod_notificacion",
                "rut_socia"
            ],
            "cabeceras" => [
                "Código",
                "Rut socia"
            ],
            "formateos" => [
                self::notificacion_destinatario_rut => "fmt_socia_rut"
            ],
            "primarias" => [
                self::notificacion_destinatario_codigo,
                self::notificacion_destinatario_rut
            ],
            "dependencias" => [
                self::notificacion_destinatario_codigo => [
                    self::notificacion_respuesta => self::notificacion_respuesta_codigo
                ],
                self::notificacion_destinatario_rut => [
                    self::notificacion_respuesta => self::notificacion_respuesta_rut
                ]
            ]
        ],
        "notificacion_respuesta" => [
            "alias" => "nr",
            "definicion" => [
                "cod_notificacion",
                "rut_socia",
                "num_respuesta",
                "fecha_respuesta",
                "respuesta"
            ],
            "cabeceras" => [
                "Código",
                "Rut socia",
                "Número",
                "Fecha",
                "Descripción"
            ],
            "formateos" => [
                self::notificacion_respuesta_rut => "fmt_socia_rut",
                self::notificacion_respuesta_fecha => "fmt_fecha"
            ],
            "primarias" => [
                self::notificacion_respuesta_codigo,
                self::notificacion_respuesta_rut,
                self::notificacion_respuesta_numero
            ]
        ],
        "notificacion" => [
            "alias" => "n",
            "definicion" => [
                "cod_notificacion",
                "socia_remitente",
                "fecha_notificacion",
                "asunto",
                "descripcion"
            ],
            "cabeceras" => [
                "Código",
                "Remitente",
                "Fecha",
                "Asunto",
                "Descripción"
            ],
            "formateos" => [
                self::notificacion_remitente => "fmt_socia_rut",
                self::notificacion_fecha => "fmt_fecha"
            ],
            "primarias" => [self::notificacion_codigo],
            "dependencias" => [
                self::notificacion_codigo => [
                    self::notificacion_destinatario => self::notificacion_destinatario_codigo,
                    self::notificacion_respuesta => self::notificacion_respuesta_codigo
                ]
            ]
        ],
        "cuota" => [
            "alias" => "c",
            "definicion" => [
                "rut_socia",
                "año_cuota",
                "num_cuota",
                "fecha_pago"
            ],
            "cabeceras" => [
                "Rut socia",
                "Año",
                "Número",
                "Fecha pago"
            ],
            "formateos" => [
                self::cuota_fecha => "fmt_fecha"
            ],
            "primarias" => [
                self::cuota_rut,
                self::cuota_año,
                self::cuota_numero
            ]
        ],
        "valor_cuota" => [
            "alias" => "vc",
            "definicion" => [
                "año_cuota",
                "valor_ingreso",
                "valor_mensual"
            ],
            "cabeceras" => [
                "Año",
                "Valor Ingreso",    
                "Valor Mensual"
            ],
            "primarias" => [
                self::valor_cuota_año
            ],
            "dependencias" => [
                self::valor_cuota_año => [
                    self::cuota => self::cuota_año
                ]
            ]
        ],
        "transaccion" => [
            "alias" => "t",
            "definicion" => [
                "num_trans",
                "fecha_trans",
                "monto",
                "descripcion"
            ],
            "cabeceras" => [
                    "Número",
                "Fecha",
                "Monto",
                "Descripción"
            ],
            "formateos" => [
                self::transaccion_fecha => "fmt_fecha"
            ],
            "primarias" => [
                self::transaccion_numero
            ]
        ]
    ];

    // -------------------------------------------------------------------------------------
    // DEFINICION DE CONSTANTES
    // -------------------------------------------------------------------------------------

    // constantes para socia
    const socia_rut = 0;
    const socia_nombre = 1;
    const socia_paterno = 2;
    const socia_materno = 3;
    const socia_fono = 4;
    const socia_direccion = 5;
    const socia_email = 6;
    const socia_estado = 7;
    const socia_permiso = 8;
    const socia_contraseña = 9;
    const socia_ingreso = 10;
    const socia_eliminacion = 11;
    const socia_conectada = 12;
    const socia = 0;

    // constantes para asistencia
    const asistencia_fecha = 0;
    const asistencia_rut = 1;
    const asistencia_asistio = 2;
    const asistencia = 1;
    
    // constantes para evento
    const evento_fecha = 0;
    const evento_tipo = 1;
    const evento_descripcion = 2;
    const evento = 2;

    // constantes para solicitud_maquina
    const solicitud_maquina_rut = 0;
    const solicitud_maquina_codigo = 1;
    const solicitud_maquina_numero = 2;
    const solicitud_maquina_fecha = 3;
    const solicitud_maquina_estado = 4;
    const solicitud_maquina_turno = 5;
    const solicitud_maquina = 3;

    // constantes para maquina
    const maquina_codigo = 0;
    const maquina_numero = 1;
    const maquina_marca = 2;
    const maquina_modelo = 3;
    const maquina_estado = 4;
    const maquina_ingreso = 5;
    const maquina_mantencion = 6;
    const maquina_uso = 7;
    const maquina = 4;

    // constantes para tipo_maquina
    const tipo_maquina_codigo = 0;
    const tipo_maquina_descripcion = 1;
    const tipo_maquina_cantidad = 2;
    const tipo_maquina = 5;

    // constantes para notificacion_destinatario
    const notificacion_destinatario_codigo = 0;
    const notificacion_destinatario_rut = 1;
    const notificacion_destinatario = 6;

    // constantes para notificacion_respuesta
    const notificacion_respuesta_codigo = 0;
    const notificacion_respuesta_rut = 1;
    const notificacion_respuesta_numero = 2;
    const notificacion_respuesta_fecha = 3;
    const notificacion_respuesta_descripcion = 4;
    const notificacion_respuesta = 7;

    // constantes para notificacion
    const notificacion_codigo = 0;
    const notificacion_remitente = 1;
    const notificacion_fecha = 2;
    const notificacion_asunto = 3;
    const notificacion_descripcion = 4;
    const notificacion = 8;

    // constantes para cuota
    const cuota_rut = 0;
    const cuota_año = 1;
    const cuota_numero = 2;
    const cuota_fecha = 3;
    const cuota = 9;

    // constantes para valor_cuota
    const valor_cuota_año = 0;
    const valor_cuota_ingreso = 1;
    const valor_cuota_mensual = 2;
    const valor_cuota = 10;

    // constantes para transaccion
    const transaccion_numero = 0;
    const transaccion_fecha = 1;
    const transaccion_monto = 2;
    const transaccion_descripcion = 3;
    const transaccion = 11;

    // -------------------------------------------------------------------------------------
    // DEFINICION DE FORMATOS POR DEFECTO
    // -------------------------------------------------------------------------------------

    // formateos para las salidas
    public static function fmt_socia_rut($rut) {
            return $rut."-".digito_verificador($rut);
    }
    public static function fmt_socia_estado($estado) {
        switch ($estado) {
            case "1": return "Activa"; break;
            case "2": return "Inactiva"; break;
            case "3": return "Eliminada"; break;
        }
    }
    public static function fmt_socia_permiso($permiso) {
        switch ($permiso) {
            case "-1": return "Administrador"; break;
            case "0": return "Presidenta"; break;
            case "1": return "Secretaria"; break;
            case "2": return "Tesorera"; break;
            case "3": return "Socia"; break;
        }
    }
    public static function fmt_fecha($fecha) {
        $arr = explode("-",$fecha);
        return implode("/",array_reverse($arr));
    }
    public static function fmt_fecha_tiempo($fecha) {
        $fh = explode(" ",$fecha);
        $f = explode("-",$fh[0]);
        $h = explode(":",$fh[1]);
        return implode("/",array_reverse($f))." ".implode(":",[$h[0],$h[1]]);
    }
    public static function fmt_evento_tipo($tipo) {
        switch ($tipo) {
            case "O": return "Reunión Ordinaria"; break;
            case "E": return "Reunión Extraordinaria"; break;
            case "T": return "Taller"; break;
        }
    }
    public static function fmt_solicitud_maquina_estado($estado) {
        switch ($estado) {
            case "0": return "Pendiente"; break;
            case "1": return "Aceptada"; break;
            case "2": return "Invalidada"; break;
            case "3": return "Validada"; break;
            case "4": return "Rechazada"; break;
        }
    }
    public static function fmt_maquina_estado($estado) {
        switch ($estado) {
            case "O": return "Operativa"; break;
            case "D": return "Defectuosa"; break;
            case "M": return "En Mantención"; break;
        }
    }

    // -------------------------------------------------------------------------------------
    // LLAMADAS DE FUNCIONES ESTATICAS
    // -------------------------------------------------------------------------------------
    public static function __callStatic($funcion, $arg)
    {
        // se verifica si la funcion corresponde a una tabla definida en la base
        $tablas = array_keys(self::base);
        foreach ($tablas as $id => $tabla) {
            if ($funcion == $tabla) {
                $n_arg = count($arg);
                if ($n_arg > 0) {
                    if ($n_arg == 1) {
                        if (is_array($arg[0])) {
                            return new tabla($id,$arg[0]);
                        } else if (is_string($arg[0])) {
                            $obj = new tabla($id);
                            $obj->alias = $arg[0];
                            return $obj;
                        } else if (is_numeric($arg[0])) {
                            $obj = new tabla($id);
                            $obj->alias .= $arg[0];
                            return $obj;
                        }
                    } else if ($n_arg == 2) {
                        $obj = new tabla($id,$arg[1]);
                        if (is_string($arg[0])) {
                            $obj->alias = $arg[0];
                        } else if (is_numeric($arg[0])) {
                            $obj->alias .= $arg[0];
                        }
                        return $obj;
                    }
                } else {
                    return new tabla($id);
                }
            }
        }

        // si se quiere obtener campos o cabeceras
        foreach ($tablas as $id => $tabla) {
            if (substr($funcion,0,strlen($tabla)) === $tabla) {
                $header = false;
                $def = false;
                $all = false;
                $id = -1;
                if (substr($funcion,strlen($funcion)-strlen("_head"),strlen("_head")) === "_head") {
                    $funcion = str_replace("_head", "", $funcion);
                    $header = true;
                } else if (substr($funcion,strlen($funcion)-strlen("_def"),strlen("_def")) === "_def") {
                    $funcion = str_replace("_def", "", $funcion);
                    $def = true;
                } else if (substr($funcion,strlen($funcion)-strlen("_all"),strlen("_all")) === "_all") {
                    $funcion = str_replace("_all", "", $funcion);
                    $all = true;
                }
                eval("
                    if (orm::$funcion !== null) {
                        \$id = orm::$funcion;
                    }
                ");
                if ($id != -1) {
                    if ($all == false) {
                        if ($def == false) {
                            if ($header == false) {
                                $alias = orm::base[$tabla]["alias"];
                                if (count($arg) > 0) {
                                    if (is_string($arg[0])) {
                                        $alias = $arg[0];
                                    } else if (is_numeric($arg[0])) {
                                        $alias .= $arg[0];
                                    }
                                }
                                $campo = orm::base[$tabla]["definicion"][$id];
                                return "$alias.$campo";
                            } else {
                                return orm::base[$tabla]["cabeceras"][$id];
                            }
                        } else {
                            $alias = orm::base[$tabla]["alias"];
                            if (count($arg) > 0) {
                                if (is_string($arg[0])) {
                                    $alias = $arg[0];
                                } else if (is_numeric($arg[0])) {
                                    $alias .= $arg[0];
                                }
                            }
                            $campo = orm::base[$tabla]["definicion"][$id];
                            $head = orm::base[$tabla]["cabeceras"][$id];
                            return [$head => "$alias.$campo"];
                        }    
                    } else {
                        $alias = orm::base[$tabla]["alias"];
                        if (count($arg) > 0) {
                            if (is_string($arg[0])) {
                                $alias = $arg[0];
                            } else if (is_numeric($arg[0])) {
                                $alias .= $arg[0];
                            } else if (is_array($arg[0])) {
                                $arg[1] = $arg[0];
                            }
                        }
                        $campos = orm::base[$tabla]["definicion"];
                        $heads = orm::base[$tabla]["cabeceras"];
                        $resultado = [];
                        foreach ($campos as $i => $campo) {
                            if (count($arg) == 2) {
                                if (is_array($arg[1])) {
                                    $encontro = false;
                                    foreach ($arg[1] as $id_campo) {
                                        if ($id_campo == $i) {
                                            $encontro = true;
                                        }
                                    }
                                    if ($encontro == true) continue;
                                }
                            }
                            $resultado[$heads[$i]] = "$alias.".$campo;
                        }
                        return $resultado;
                    }                    
                }
                return null;
            }
        }


        // se verifica si es una funcion de ayuda a la generacion de sql
        switch ($funcion)
        {
            case "concat":
                return "concat(".implode(",' ',",$arg).")";
            break;
            case "round":
            case "count":
            case "sum":
            case "avg":
            case "max":
            case "min":
                return "$funcion(".$arg[0].")";
            break;
            case "ifnull":
                return "ifnull(".$arg[0].",".$arg[1].")";
            break;
            case "and":
            case "or":
                return "(".implode(" $funcion ",$arg).")";
            break;
            case "like":
                return $arg[0]." like '".$arg[1]."'";
            break;
            case "is_not":
                return implode(" is not ",$arg);
            break;
            case "val":
                return "'".$arg[0]."'";
            break;
            case "header":
                return "`".$arg[0]."`";
            break;
            case "equal":
                return $arg[0]." = ".$arg[1];
            break;
            case "equav":
                return $arg[0]." = '".$arg[1]."'";
            break;
            case "opr":
                return implode(" ".$arg[0]." ",$arg[1]);
            break;
            case "in":
            case "all":
            case "any":
                // se ve si el segundo argumento es un arreglo o un string
                if (is_string($arg[1])) {
                    return $arg[0]." $funcion (".$arg[1].")";
                } else if (is_array($arg[1])) {
                    return $arg[0]." $funcion (".implode(",",$arg[1]).")";
                } else return "";
            break;
            case "not_in":
                // se ve si el segundo argumento es un arreglo o un string
                if (is_string($arg[1])) {
                    return $arg[0]." not in (".$arg[1].")";
                } else if (is_array($arg[1])) {
                    return $arg[0]." not in (".implode(",",$arg[1]).")";
                } else return "";
            break;
        }
        return null;
    }
    
    // para obtener todas las condiciones de equivalencia por joins
    static function join(...$objetos)
    {
        // se recorren los objetos, TODOS deben ser objeto-orminio
        $todos = true;
        foreach ($objetos as $objeto)
        {
            if (get_class($objeto) != "tabla")
            {
                $todos = false;
                break;
            }
        }
        if ($todos = true)
        {
            // arreglo que obtiene los joins
            $joins = [];

            // obtenemos los nombres de las tablas
            $tablas = array_keys(orm::base);
            
            // objeto dependiente
            foreach ($objetos as $i => $dep)
            {
                // el objeto dependiente debe tener dependencias
                if ($dep->dependencias != null)
                {
                    // se recorren las dependencias de dependiente
                    foreach ($dep->dependencias as $dep_key => $dep_arr)
                    {
                        // se recorre el arreglo de dependencias
                        foreach ($dep_arr as $ind_tab => $ind_key)
                        {
                            // se recorre para obtener el objeto independiente
                            foreach($objetos as $j => $ind)
                            {
                                // dependiente != independiente
                                if ($i != $j)
                                {
                                    // si el nombre de la tabla coincide
                                    if ($ind->nombre == $tablas[$ind_tab])
                                    {
                                        // obtenemos los datos de la tabla dependiente
                                        $dep_campo = $dep->key($dep_key);
                                        $ind_campo = $ind->key($ind_key);
                                        $joins[] = orm::equal($dep_campo,$ind_campo);
                                    }
                                }
                            }
                        }
                    }
                } 
            }
            
            // si existen joins
            if (count($joins) > 0) return implode(" and ",$joins);
        }
        else return "";
    }
    
    /***************************************************************************
    // realizar consulta directa a la base de datos
    entrada = [
        "conexion" => $conexion,
        "sql" => $sql,
        "formato" => [
           "cabecera" => "fmt_funcion",
           ...
        ],
        "agrupar" => [
            "cabecera",
            ...
        ],
        "cabeceras" => true,
        "valores" => true,
        "json" => false
    ]
    ***************************************************************************/
    private static function consultar($entrada)
    {
        // la entrada debe ser un arreglo
        if (is_array($entrada))
        {
            // debe tener un campo conexion
            if (array_key_exists("conexion",$entrada))
            {
                // debe tener un campo sql
                if (array_key_exists("sql",$entrada))
                {
                    $conexion = $entrada["conexion"];
                    $sql = $entrada["sql"];

                    // se verifica si la consulta tiene un select
                    $query = false;
                    if (strpos($sql, 'select') !== false) {
                        $query = true;
                    }

                    // si es una query
                    if ($query == true)
                    {
                        // si existe el campo formato se modifica
                        $formato = null;
                        if (array_key_exists("formato", $entrada)) {
                            $formato = $entrada["formato"];
                        }

                        // si existe el campo cabeceras se modifica
                        $cabeceras = true;
                        if (array_key_exists("cabeceras", $entrada)) {
                            $cabeceras = $entrada["cabeceras"];
                        }

                        // para solo mostrar los valores
                        $valores = true;
                        if (array_key_exists("valores", $entrada)) {
                            $valores = $entrada["valores"];
                        }
                        if ($valores == false) $cabeceras = false;

                        // para codificar la salida en json o no
                        $json = false;
                        if (array_key_exists("json", $entrada)) {
                            $json = $entrada["json"];
                        }
                        $agrupar = null;
                        if (array_key_exists("grupo",$entrada)) {
                            $agrupar = $entrada["grupo"];
                        }

                        // se realiza la consulta
                        $salida = $conexion->sql_query($sql);

                        // si la salida tiene al menos algun resultado
                        if ($salida != null)
                        {
                            // para guardar el resultado
                            $resultado = [];

                            // si se desean ver solo los valores
                            if ($valores == true)
                            {
                                // para saber si se requiere agrupar por algunos campos
                                if ($agrupar != null) 
                                {
                                    // si se quieren ver las cabeceras
                                    if ($cabeceras == true) 
                                    {
                                        $heads = array_keys($salida[0]);
                                        $r1 = [];
                                        $r2 = [];
                                        foreach ($heads as $id => $head) {
                                            $encontro = false;
                                            foreach ($entrada["grupo"] as $head_in) {
                                                if ($head == $head_in) {
                                                    $r1[] = $head_in;
                                                    $encontro = true;
                                                }
                                            }
                                            if ($encontro == false) {
                                                $r2[] = $head;
                                            }
                                        }
                                        $r1[] = $r2;
                                        $resultado[] = $r1; 
                                        $grp_index = 0;
                                    } else {
                                        $grp_index = -1;
                                    }

                                    // se obtiene la salida
                                    $grp_heads = [];
                                    foreach ($salida as $registro) {

                                        // si se quiere formatear la salida
                                        if ($formato != null) 
                                        {
                                            foreach ($formato as $cabecera => $funcion) {
                                                if (is_string($funcion)) {
                                                    $registro[$cabecera] = orm::$funcion($registro[$cabecera]);
                                                } else {
                                                    $registro[$cabecera] = $funcion($registro[$cabecera]);
                                                }
                                            }
                                        }

                                        // para agrupar los valores
                                        $r1 = [];
                                        $r2 = [];
                                        foreach ($registro as $head => $valor) {
                                            $encontro = false;
                                            foreach ($entrada["grupo"] as $head_in) {
                                                if ($head == $head_in) {
                                                    $r1[] = $valor;
                                                    $encontro = true;
                                                }
                                            }
                                            if ($encontro == false) {
                                                $r2[] = $valor;
                                            }
                                        }
                                        
                                        if ($r1 !== $grp_heads) {
                                            $grp_heads = array_splice($r1,0,count($r1));
                                            $grp_index++;
                                            $resultado[$grp_index] = $grp_heads;
                                            $resultado[$grp_index][] = [$r2];
                                        } else {
                                            $resultado[$grp_index][count($grp_heads)][] = $r2;
                                        }
                                    }

                                } else {

                                    // si se quieren ver las cabeceras
                                    if ($cabeceras == true) {
                                        $resultado[] = array_keys($salida[0]);
                                    }

                                    // se obtiene la salida
                                    foreach ($salida as $registro) {

                                        // si se quiere formatear la salida
                                        if ($formato != null) 
                                        {
                                            foreach ($formato as $cabecera => $funcion) {
                                                if (is_string($funcion)) {
                                                    $registro[$cabecera] = orm::$funcion($registro[$cabecera]);
                                                } else {
                                                    $registro[$cabecera] = $funcion($registro[$cabecera]);
                                                }
                                            }
                                        }

                                        // se agrega la salida
                                        $resultado[] = array_values($registro);
                                    }
                                }   
                            
                            } else {

                                // para saber si se requiere agrupar por algunos campos
                                if ($agrupar != null) 
                                {
                                    // se obtiene la salida
                                    $grp_index = -1;
                                    $grp_heads = [];
                                    foreach ($salida as $registro) {

                                        // si se quiere formatear la salida
                                        if ($formato != null) 
                                        {
                                            foreach ($formato as $cabecera => $funcion) {
                                                if (is_string($funcion)) {
                                                    $registro[$cabecera] = orm::$funcion($registro[$cabecera]);
                                                } else {
                                                    $registro[$cabecera] = $funcion($registro[$cabecera]);
                                                }
                                            }
                                        }

                                        // para agrupar los valores
                                        $r1 = [];
                                        $r2 = [];
                                        foreach ($registro as $head => $valor) {
                                            $encontro = false;
                                            foreach ($entrada["grupo"] as $head_in) {
                                                if ($head == $head_in) {
                                                    $r1[$head] = $valor;
                                                    $encontro = true;
                                                }
                                            }
                                            if ($encontro == false) {
                                                $r2[$head] = $valor;
                                            }
                                        }
                                        
                                        if ($r1 !== $grp_heads) {
                                            $grp_heads = array_splice($r1,0,count($r1));
                                            $grp_index++;
                                            $resultado[$grp_index] = $grp_heads;
                                            $resultado[$grp_index][] = [$r2];
                                        } else {
                                            $resultado[$grp_index][0][] = $r2;
                                        }
                                    }

                                } else {

                                    // se obtiene la salida
                                    foreach ($salida as $registro) {

                                        // si se quiere formatear la salida
                                        if ($formato != null) 
                                        {
                                            foreach ($formato as $cabecera => $funcion) {
                                                if (is_string($funcion)) {
                                                    $registro[$cabecera] = orm::$funcion($registro[$cabecera]);
                                                } else {
                                                    $registro[$cabecera] = $funcion($registro[$cabecera]);
                                                }
                                            }
                                        }

                                        // se agrega el registro a la salida
                                        $resultado[] = $registro;
                                    }
                                }
                            }

                            // se retorna la salida como arreglo
                            if ($json == false) return $resultado;
                            else return json_encode($resultado);
                        }
                    }
                    // es una ejecucion, solo retorna 1 o 0
                    else {
                        return $conexion->sql_execute($sql);
                    }   
                }
            }
        }
        return null;
    }

    // -------------------------------------------------------------------------------------
    // FUNCIONES PARA CONSULTAR A LA BASE DE DATOS
    // -------------------------------------------------------------------------------------

    // busqueda simple por find
    public static function find($objeto, $campos = null, $salida = null)
    {
        // para convertir $objeto si es una id de tabla
        if (is_numeric($objeto)) {
            $tablas = array_keys(orm::base);
            if (isset($tablas[$objeto])) {
                $objeto = new tabla($objeto);
            }
        }

        // el objeto debe ser un objeto tabla
        if (get_class($objeto) == "tabla")
        {
            // se obtiene la tabla y el alias
            $tabla = $objeto->nombre;
            $alias = $objeto->alias;
            
            // para obtener el arreglo de formatos
            $formato = [];

            // para saber si el arreglo de campos es la conexion en si
            $es_conexion = false;
            if ($salida == null) {
                if (is_array($campos)) {
                    if (array_key_exists("conexion", $campos)) {
                        $es_conexion = true;
                    }
                } else if (get_class($campos) == "Conexion") {
                    $es_conexion = true;
                }
            }
            if ($es_conexion == true) $salida = $campos;
            
            // si no hay campos definidos, se seleccionan todos
            $seleccion = [];
            $orden = [];
            if (count($campos) == 0 || $es_conexion == true)
            {
                // se recorre todos los campos del objeto
                foreach ($objeto->definicion as $i => $campo) {
                    $head  = $objeto->head($i);
                    $campo = $objeto->key($i);
                    $seleccion[] = "$campo '$head'";

                    // si existe formato
                    if ($salida != null) {
                        $f = true;
                        if (is_array($salida)) {
                            if (array_key_exists("formato",$salida)) {
                                $f = $salida["formato"];
                            }
                        }

                        // si se requiere formatear
                        if ($f === true) {
                            if (isset($objeto->formatos[$i])) {
                                $func = $objeto->formatos[$i];
                                $formato[$head] = $func;
                            }
                        } else if (is_array($f)) {
                            foreach ($f as $fmt_campo => $fmt_funcion) {
                                if ($fmt_campo == $i) {
                                    if ($fmt_funcion === true) {
                                        if (isset($objeto->formatos[$i])) {
                                            $fmt_funcion = $objeto->formatos[$i];
                                            $formato[$head] = $fmt_funcion;
                                        }
                                    } else {
                                        $formato[$head] = $fmt_funcion;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            // de lo contrario se seleccionan solo los que se definen
            else
            {
                // se reccorre el arreglo de campos
                foreach ($campos as $i => $id_orden) {
                    
                    // verificamos si id_orden es un string
                    $dir_orden = null;
                    if (is_string($id_orden) == true) {
                        $dir_orden = $id_orden;
                        $id = $i;
                    } else {
                        $id = $id_orden;
                    }
                    
                    // se obtiene el campo y la cabecera
                    $head  = $objeto->cabeceras[$id];
                    $campo = $objeto->key($id);
                    $seleccion[] = "$campo '$head'";
                    
                    // si existe orden, se agrega al arreglo de orden
                    if ($dir_orden != null) {
                        $orden[$campo] = $dir_orden;
                    }

                    // si existe formato
                    if ($salida != null) {
                        $f = true;
                        if (is_array($salida)) {
                            if (array_key_exists("formato",$salida)) {
                                $f = $salida["formato"];
                            }
                        }

                        // si se requiere formatear
                        if ($f === true) {
                            if (isset($objeto->formatos[$id])) {
                                $func = $objeto->formatos[$id];
                                $formato[$head] = $func;
                            }
                        } else if (is_array($f)) {
                            foreach ($f as $fmt_campo => $fmt_funcion) {
                                if ($fmt_campo == $id) {
                                    if ($fmt_funcion === true) {
                                        if (isset($objeto->formatos[$id])) {
                                            $fmt_funcion = $objeto->formatos[$id];
                                            $formato[$head] = $fmt_funcion;
                                        }
                                    }
                                    if (is_callable($fmt_funcion)) {
                                        $formato[$head] = $fmt_funcion;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            // se convierten los datos a formato sql
            $seleccion = implode(",",$seleccion);
            
            // se genera el sql a traves de los datos obtenidos
            $sql = "(select $seleccion from $tabla $alias";
            
            // si hay condicion
            $condicion = $objeto->cond();
            if ($condicion != "") {
                $sql .= " where $condicion";
            }
            
            // si existen elementos para ordenar
            if (count($orden) > 0) {
                $resultado_orden = [];
                foreach ($orden as $campo => $direccion) {
                    $resultado_orden[] = "$campo $direccion";
                }
                $resultado_orden = implode(",", $resultado_orden);
                if ($resultado_orden != "") {
                    $sql .= " order by $resultado_orden";
                }
            }
            
            // se cierra el sql
            $sql .= ")";
            
            // si existe conexion de salida
            $conexion = null;
            if ($salida != null) 
            {    
                // verificamos que sea un arreglo
                if (is_array($salida)) 
                {
                    // verificamos si salida tiene un campo conexion
                    if (array_key_exists("conexion", $salida)) 
                    {
                        // verificamos que la salida sea una conexion
                        if (get_class($salida["conexion"]) == "Conexion") {
                            $conexion = $salida["conexion"];
                        }
                    }
                } 
                else 
                {
                    // verificamos que la salida sea una conexion
                    if (get_class($salida) == "Conexion") {
                        $conexion = $salida;
                    }
                }
            }
            
            // si existe conexion, se retorna el arreglo obtenido
            if ($conexion != null) {
                
                if (count($formato) == 0) $formato = null;
                $cabeceras = true;
                if (is_array($salida)) {
                    if (array_key_exists("cabeceras", $salida)) {
                        $cabeceras = $salida["cabeceras"];
                    }
                }
                $valores = true;
                if (is_array($salida)) {
                    if (array_key_exists("valores", $salida)) {
                        $valores = $salida["valores"];
                    }
                }
                $json = false;
                if (is_array($salida)) {
                    if (array_key_exists("json", $salida)) {
                        $json = $salida["json"];
                    }
                }
                $grupo = null;
                if (is_array($salida)) {
                    if (array_key_exists("grupo", $salida)) {
                        foreach ($salida["grupo"] as $i => $id_campo) {
                            $head = $objeto->head($id_campo);
                            $salida["grupo"][$i] = $head;
                        }
                        $grupo = $salida["grupo"];
                    }
                }
                $obj = false;
                if (is_array($salida)) {
                    if (array_key_exists("objeto", $salida)) {
                        $obj = $salida["objeto"];
                    }
                }
                if ($obj == true) {
                    $cabeceras = false;
                    $valores = false;
                    $json = false;
                    $grupo = null;
                }
                $resultado = orm::consultar([
                    "conexion" => $conexion,
                    "sql" => $sql,
                    "formato" => $formato,
                    "cabeceras" => $cabeceras,
                    "valores" => $valores,
                    "grupo" => $grupo,
                    "json" => $json
                ]);
                if ($obj == false) return $resultado;
                else {

                    // si hay resultados
                    if (count($resultado) > 0) {
                        foreach ($resultado as $i => $registro) {
                            // creamos un nuevo objeto
                            $nuevo = new tabla($objeto->id);
                            // recorremos el arreglo del resultado de salida
                            foreach ($registro as $head => $valor) {
                                // buscamos el id del campo
                                foreach ($objeto->cabeceras as $j => $cabecera) {
                                    if ($head == $cabecera) {
                                        $nuevo->set($j,$valor);
                                    }
                                }
                            }
                            $resultado[$i] = $nuevo;
                        }
                        return $resultado;
                    }  
                }
            }
            // de lo contrario, solo se retorna el sql obtenido
            else return $sql;
        }
        return null;
    }
    
    // busqueda compleja por select
    public static function select($arreglo)
    {
        if (is_array($arreglo))
        {
            // debe existir la clave tablas
            if (array_key_exists("tablas", $arreglo))
            {
                // ahora se obtiene el valor en tablas
                $tablas = $arreglo["tablas"];
                
                // para validar si es una tabla
                $valido = true;

                // para saber si es singular o multiple
                $singular = false;

                // vemos si es un arreglo (varias tablas en un select)
                if (is_array($tablas))
                {
                    foreach ($tablas as $i => $tabla) {
                        if (is_numeric($tabla)) {
                            $tab = array_keys(orm::base);
                            if (isset($tab[$tabla])) {
                                $tablas[$i] = new tabla($tabla);
                            } else {
                                $valido = false;
                            }
                        } else {
                            if (get_class($tabla) != "tabla") {
                                $valido = false;
                            }
                        }
                    }
                }
                else if (is_numeric($tablas)) {
                    $tab = array_keys(orm::base);
                    if (isset($tab[$tablas])) {
                        $tablas = new tabla($tablas);
                        $singular = true;
                    } else {
                        $valido = false;
                    }
                }
                // si es solo un objeto tabla
                else if (get_class($tablas) == "tabla")
                {
                    // es singular
                    $singular = true;
                }
                else {
                    $valido = false;
                }
                
                // si la definicion de tablas es valido
                if ($valido == true)
                {
                    // obtenemos las tablas
                    $sql_tablas = [];
                    
                    // se obtienen las propiedades
                    $joins = true;
                    $condicionar = true;
                    $agrupar = true;
                    if (array_key_exists("prop", $arreglo)) {
                        if (array_key_exists("join", $arreglo["prop"])) {
                            $joins = $arreglo["prop"]["join"];
                        }
                        if (array_key_exists("condicionar", $arreglo["prop"])) {
                            $condicionar = $arreglo["prop"]["condicionar"];
                        }
                        if (array_key_exists("agrupar", $arreglo["prop"])) {
                            $agrupar = $arreglo["prop"]["agrupar"];
                        }
                    }

                    // si no es singular
                    if ($singular == false)
                    {
                        foreach ($tablas as $tabla) {
                            $nombre = $tabla->nombre;
                            $alias = $tabla->alias;
                            $sql_tablas[] = "$nombre $alias";
                        }
                    }
                    // si es singular
                    else
                    {
                        $nombre = $tablas->nombre;
                        $alias = $tablas->alias;
                        $sql_tablas[] = "$nombre $alias";
                    }

                    // obtenemos los campos
                    $sql_campos = [];
                    $grp_campos = [];
                    
                    // para obtener el arreglo de formatos
                    $fmt_arr = [];
                    $formatear = true;
                    if (array_key_exists("salida", $arreglo)) {
                        if (is_array($arreglo["salida"])) {
                            if (array_key_exists("formato",$arreglo["salida"])) {
                                $formatear = $arreglo["salida"]["formato"];
                            }
                        }
                    }

                    // si hay campos definidos
                    if (array_key_exists("campos", $arreglo))
                    {
                        // recorremos los campos definidos
                        foreach ($arreglo["campos"] as $cabecera => $definicion)
                        {
                            // obtenemos el campo
                            $campo_resultado = [];
                            if (is_numeric($cabecera) && is_numeric($definicion)) {
                                if ($singular == true) {
                                    $campo = $tablas->key($definicion);
                                    $head = $tablas->head($definicion);
                                    if ($campo != null && $head != null) {
                                        $campo_resultado = [$head => $campo];
                                        if ($formatear === true) {
                                            if (isset($tablas->formatos[$definicion])) {
                                                $func = $tablas->formatos[$definicion];
                                                $fmt_arr[$head] = $func;
                                            }
                                        } else if (is_array($formatear)) {
                                            foreach ($formatear as $fmt_campo => $fmt_funcion) {
                                                if ($fmt_campo == $definicion) {
                                                    if ($fmt_funcion === true) {
                                                        if (isset($tablas->formatos[$definicion])) {
                                                            $fmt_funcion = $tablas->formatos[$definicion];
                                                            $fmt_arr[$head] = $fmt_funcion;
                                                        }
                                                    }
                                                    if (is_callable($fmt_funcion)) {
                                                        $fmt_arr[$head] = $fmt_funcion;
                                                    }
                                                    unset($arreglo["salida"]["formato"][$fmt_campo]);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            else if (is_numeric($cabecera) && is_string($definicion)) {
                                $campo_resultado = [0 => $definicion];
                                if ($formatear === true) {
                                    if ($singular == true) {
                                        if ($tablas->formatos != null) {
                                            foreach ($tablas->formatos as $id => $funcion) {
                                                if ($definicion == $tablas->key($id)) {
                                                    $fmt_arr[$definicion] = $funcion;
                                                }
                                            }
                                        }
                                    } else {
                                        foreach ($tablas as $tabla) {
                                            if ($tabla->formatos != null) {
                                                foreach ($tabla->formatos as $id => $funcion) {
                                                    if ($definicion == $tabla->key($id)) {
                                                        $fmt_arr[$definicion] = $funcion;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                } else if (is_array($formatear)) {
                                    if ($singular == true) {
                                        foreach ($formatear as $fmt_campo => $fmt_funcion) {
                                            if ($fmt_campo == $definicion) {
                                                if ($tablas->formatos != null) {
                                                    foreach ($tablas->formatos as $id => $funcion) {
                                                        if ($definicion == $tablas->key($id)) {
                                                            if ($fmt_funcion === true) {
                                                                $fmt_funcion = $funcion;
                                                                $fmt_arr[$definicion] = $fmt_funcion;
                                                            }
                                                            if (is_callable($fmt_funcion)) {
                                                                $fmt_arr[$definicion] = $fmt_funcion;
                                                            }
                                                            unset($arreglo["salida"]["formato"][$fmt_campo]);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    } else {
                                        foreach ($formatear as $fmt_campo => $fmt_funcion) {
                                            if ($fmt_campo == $definicion) {
                                                foreach ($tablas as $tabla) {
                                                    if ($tabla->formatos != null) {
                                                        foreach ($tabla->formatos as $id => $funcion) {
                                                            if ($definicion == $tablas->key($id)) {
                                                                if ($fmt_funcion === true) {
                                                                    $fmt_funcion = $funcion;
                                                                    $fmt_arr[$definicion] = $fmt_funcion;
                                                                    unset($arreglo["salida"]["formato"][$fmt_campo]);
                                                                }
                                                                if (is_callable($fmt_funcion)) {
                                                                    $fmt_arr[$definicion] = $fmt_funcion;
                                                                    unset($arreglo["salida"]["formato"][$fmt_campo]);
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }   
                                        }
                                    }      
                                }
                            }
                            else if (is_numeric($cabecera) && is_array($definicion)) {
                                $campo_resultado = $definicion;
                                foreach ($definicion as $cabecera => $campo) {
                                    if ($formatear === true) {
                                        if ($singular == true) {
                                            if ($tablas->formatos != null) {
                                                foreach ($tablas->formatos as $id => $funcion) {
                                                    if ($campo == $tablas->key($id)) {
                                                        $fmt_arr[$cabecera] = $funcion;
                                                    }
                                                }
                                            }
                                        } else {
                                            foreach ($tablas as $tabla) {
                                                if ($tabla->formatos != null) {
                                                    foreach ($tabla->formatos as $id => $funcion) {
                                                        if ($campo == $tabla->key($id)) {
                                                            $fmt_arr[$cabecera] = $funcion;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    } else if (is_array($formatear)) {
                                        if ($singular == true) {
                                            foreach ($formatear as $fmt_campo => $fmt_funcion) {
                                                if ($fmt_campo == $cabecera) {
                                                    if ($tablas->formatos != null) {
                                                        foreach ($tablas->formatos as $id => $funcion) {
                                                            if ($campo == $tablas->key($id)) {
                                                                if ($fmt_funcion === true) {
                                                                    $fmt_funcion = $funcion;
                                                                    $fmt_arr[$cabecera] = $fmt_funcion;
                                                                }
                                                                if (is_callable($fmt_funcion)) {
                                                                    $fmt_arr[$cabecera] = $fmt_funcion;
                                                                }
                                                                unset($arreglo["salida"]["formato"][$fmt_campo]);
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        } else {
                                            foreach ($formatear as $fmt_campo => $fmt_funcion) {
                                                if ($fmt_campo == $cabecera) {
                                                    foreach ($tablas as $tabla) {
                                                        if ($tabla->formatos != null) {
                                                            foreach ($tabla->formatos as $id => $funcion) {
                                                                if ($campo == $tabla->key($id)) {
                                                                    if ($fmt_funcion === true) {
                                                                        $fmt_funcion = $funcion;
                                                                        $fmt_arr[$cabecera] = $fmt_funcion;
                                                                    }
                                                                    if (is_callable($fmt_funcion)) {
                                                                        $fmt_arr[$cabecera] = $fmt_funcion;
                                                                    }
                                                                    unset($arreglo["salida"]["formato"][$fmt_campo]);
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }      
                                    }
                                }
                            }
                            else if (is_string($cabecera) && is_numeric($definicion)) {
                                if ($singular == true) {
                                    $campo = $tablas->key($definicion);
                                    $head = $cabecera;
                                    if ($campo != null && $head != null) {
                                        $campo_resultado = [$head => $campo];
                                        if ($formatear === true) {
                                            if (isset($tablas->formatos[$definicion])) {
                                                $func = $tablas->formatos[$definicion];
                                                $fmt_arr[$head] = $func;
                                            }
                                        } else if (is_array($formatear)) {
                                            foreach ($formatear as $fmt_campo => $fmt_funcion) {
                                                if ($fmt_campo == $definicion) {
                                                    if ($fmt_funcion === true) {
                                                        if (isset($tablas->formatos[$definicion])) {
                                                            $fmt_funcion = $tablas->formatos[$definicion];
                                                            $fmt_arr[$head] = $fmt_funcion;
                                                        }
                                                    }
                                                    if (is_callable($fmt_funcion)) {
                                                        $fmt_arr[$head] = $fmt_funcion;
                                                    }
                                                    unset($arreglo["salida"]["formato"][$fmt_campo]);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            else {
                                $campo_resultado = [$cabecera => $definicion];
                                if ($formatear === true) {
                                    if ($singular == true) {
                                        if ($tablas->formatos != null) {
                                            foreach ($tablas->formatos as $id => $funcion) {
                                                if ($definicion == $tablas->key($id)) {
                                                    $fmt_arr[$cabecera] = $funcion;
                                                }
                                            }
                                        }
                                    } else {
                                        foreach ($tablas as $tabla) {
                                            if ($tabla->formatos != null) {
                                                foreach ($tabla->formatos as $id => $funcion) {
                                                    if ($definicion == $tabla->key($id)) {
                                                        $fmt_arr[$cabecera] = $funcion;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                } else if (is_array($formatear)) {
                                    if ($singular == true) {
                                        foreach ($formatear as $fmt_campo => $fmt_funcion) {
                                            if ($fmt_campo == $cabecera) {
                                                if ($tablas->formatos != null) {
                                                    foreach ($tablas->formatos as $id => $funcion) {
                                                        if ($definicion == $tablas->key($id)) {
                                                            if ($fmt_funcion === true) {
                                                                $fmt_funcion = $funcion;
                                                                $fmt_arr[$cabecera] = $fmt_funcion;
                                                            }
                                                            if (is_callable($fmt_funcion)) {
                                                                $fmt_arr[$cabecera] = $fmt_funcion;
                                                            }
                                                            unset($arreglo["salida"]["formato"][$fmt_campo]);
                                                        }
                                                    }
                                                }
                                            } 
                                        }
                                    } else {
                                        foreach ($formatear as $fmt_campo => $fmt_funcion) {
                                            if ($fmt_campo == $cabecera) {
                                                foreach ($tablas as $tabla) {
                                                    if ($tabla->formatos != null) {
                                                        foreach ($tabla->formatos as $id => $funcion) {
                                                            if ($definicion == $tabla->key($id)) {
                                                                if ($fmt_funcion === true) {
                                                                    $fmt_funcion = $funcion;
                                                                    $fmt_arr[$cabecera] = $fmt_funcion;
                                                                }
                                                                if (is_callable($fmt_funcion)) {
                                                                    $fmt_arr[$cabecera] = $fmt_funcion;
                                                                }
                                                                unset($arreglo["salida"]["formato"][$fmt_campo]);
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }      
                                }
                            }
                            
                            // vemos si $campo_resultado tiene algo
                            if (count($campo_resultado) > 0) {
                                foreach ($campo_resultado as $h => $d) {
                                    if ($h !== 0) {
                                        $sql_campos[] = "$d '$h'";
                                    } else {
                                        $sql_campos[] = $d;
                                    }
                                    $grp_campos[] = [$d, $h];
                                }
                            }
                        }
                    }
                    else
                    {
                        if ($singular == false)
                        {
                            foreach ($tablas as $tabla) {
                                foreach ($tabla->definicion as $i => $campo) {
                                    $head = $tabla->cabeceras[$i];
                                    $alias = $tabla->alias;
                                    $sql_campos[] = "$alias.$campo '$head'";
                                    $grp_campos[] = ["$alias.$campo",$head];
                                    if ($formatear == true) {
                                        if (isset($tabla->formatos[$i])) {
                                            $func = $tabla->formatos[$i];
                                            $fmt_arr[$head] = $func;
                                        }
                                    }
                                }
                            }
                        }
                        else
                        {
                            foreach ($tablas->definicion as $i => $campo) {
                                $head = $tablas->cabeceras[$i];
                                $alias = $tablas->alias;
                                $sql_campos[] = "$alias.$campo '$head'";
                                $grp_campos[] = ["$alias.$campo",$head];
                                if ($formatear == true) {
                                    if (isset($tablas->formatos[$i])) {
                                        $func = $tablas->formatos[$i];
                                        $fmt_arr[$head] = $func;
                                    }
                                }
                            }
                        }
                    }
                    
                    // se obtiene la condicion
                    $sql_where = [];
                    if ($joins == true && $singular == false)
                    {
                        $sql_where[] = orm::join(...$tablas);
                    }
                    if ($condicionar == true) 
                    {
                        if ($singular == false)
                        {
                            foreach ($tablas as $tabla) {
                                $cond = $tabla->cond();
                                if ($cond != "") {
                                    $sql_where[] = $cond;
                                }
                            }
                        }
                        else
                        {
                            $cond = $tablas->cond();
                            if ($cond != "") {
                                $sql_where[] = $cond;
                            }
                        }
                    }
                    if (array_key_exists("where", $arreglo)) {
                        if ($arreglo["where"] != "") {
                            $sql_where[] = $arreglo["where"];
                        }
                    }
                    
                    // se obtiene la agrupacion
                    $sql_grupo = [];
                    if ($agrupar == true)
                    {
                        // recorremos los campos que existen
                        $se_debe = false;
                        foreach ($grp_campos as list($def,$head))
                        {
                            // vemos si no es una subconsulta
                            if ($def[0] != "(")
                            {
                                // buscamos alguna funcion de agrupacion
                                $func = ["count(","sum(","avg(","max(","min("];
                                $hay_func = false;
                                foreach ($func as $f) {
                                    if (strpos($def,$f) !== false) {
                                        $hay_func = true;
                                        $se_debe = true;
                                    }
                                }
                                if ($hay_func == false) {
                                    if ($head == 0) {
                                        $sql_grupo[] = $def;
                                    } else {
                                        $sql_grupo[] = orm::header($head);
                                    }
                                }
                            } else {
                                if ($head == 0) {
                                    $sql_grupo[] = $def;
                                } else {
                                    $sql_grupo[] = orm::header($head);
                                }
                            }
                        }
                        // si no se debe entonces se limpia
                        if ($se_debe == false) {
                            $sql_grupo = [];
                        }
                    }
                    if (array_key_exists("grupo",$arreglo)) {
                        foreach ($arreglo["grupo"] as $campo) {
                            if ($singular == true) {
                                if (is_numeric($campo)) {
                                    $sql_grupo[] = $tablas->key($campo);
                                }
                            } 
                            if (is_string($campo)) {
                                $sql_grupo[] = $campo;
                            }
                        }
                    }
                    
                    // para ordenar
                    $sql_orden = [];
                    if (array_key_exists("orden", $arreglo)) {
                        foreach ($arreglo["orden"] as $campo => $dir_orden) {
                            if ($singular == true) {
                                if (is_numeric($campo)) {
                                    $sql_orden[] = $tablas->key($campo)." $dir_orden";
                                } else {
                                    $sql_orden[] = "$campo $dir_orden";
                                }
                            } else {
                                $sql_orden[] = "$campo $dir_orden";
                            }
                        }
                    }
                    
                    $sql_tablas = implode(",",$sql_tablas);
                    $sql_campos = implode(",",$sql_campos);
                    $sql = "(select $sql_campos from $sql_tablas";
                    
                    $sql_where = implode(" and ",$sql_where);
                    if ($sql_where != "") $sql .= " where $sql_where";
                    
                    $sql_grupo = implode(",",$sql_grupo);
                    if ($sql_grupo != "") $sql .= " group by $sql_grupo";
                    
                    $sql_having = "";
                    if (array_key_exists("having", $arreglo)) {
                        $sql_having = $arreglo["having"];
                    }
                    if ($sql_having != "") $sql .= " having $sql_having";
                    
                    $sql_orden = implode(",",$sql_orden);
                    if ($sql_orden != "") $sql .= " order by $sql_orden";
                    
                    $sql .= ")";
                    
                    // si existe conexion de salida
                    $conexion = null;
                    if (array_key_exists("salida", $arreglo)) 
                    {    
                        // verificamos que sea un arreglo
                        $salida = $arreglo["salida"];
                        if (is_array($salida)) 
                        {
                            // verificamos si salida tiene un campo conexion
                            if (array_key_exists("conexion", $salida)) 
                            {
                                // verificamos que la salida sea una conexion
                                if (get_class($salida["conexion"]) == "Conexion") {
                                    $conexion = $salida["conexion"];
                                }
                            }
                        } 
                        else 
                        {
                            // verificamos que la salida sea una conexion
                            if (get_class($salida) == "Conexion") {
                                $conexion = $salida;
                            }
                        }
                    }

                    // si existe conexion, se retorna el arreglo obtenido
                    if ($conexion != null) {

                        if (count($fmt_arr) == 0) $fmt_arr = null;
                        $cabeceras = true;
                        $valores = true;
                        $json = false;
                        $grupo = null;
                        if (array_key_exists("salida", $arreglo)) {
                            if (is_array($arreglo["salida"])) {
                                if (array_key_exists("cabeceras", $arreglo["salida"])) {
                                    $cabeceras = $arreglo["salida"]["cabeceras"];
                                }
                                if (array_key_exists("formato", $arreglo["salida"])) {
                                    if (is_array($arreglo["salida"]["formato"])) {
                                        if (count($arreglo["salida"]["formato"]) > 0) {
                                            foreach ($arreglo["salida"]["formato"] as $cabecera => $funcion) {
                                                $fmt_arr[$cabecera] = $funcion;
                                            }
                                        }
                                    }
                                }
                                if (array_key_exists("valores", $arreglo["salida"])) {
                                    $valores = $arreglo["salida"]["valores"];
                                }
                                if (array_key_exists("json", $arreglo["salida"])) {
                                    $json = $arreglo["salida"]["json"];
                                }
                                if (array_key_exists("grupo", $arreglo["salida"])) {
                                    $grupo = $arreglo["salida"]["grupo"];
                                }
                            }
                        }
                        return orm::consultar([
                            "conexion" => $conexion,
                            "sql" => $sql,
                            "formato" => $fmt_arr,
                            "cabeceras" => $cabeceras,
                            "valores" => $valores,
                            "grupo" => $grupo,
                            "json" => $json
                        ]);
                    }
                    // de lo contrario, solo se retorna el sql obtenido
                    else return $sql;
                }
            }
        }
        return null;
    }

    // insertar un nuevo registro en la base de datos
    public static function insert(tabla $objeto, Conexion $conexion = null)
    {
        // se obtiene el nombre de la tabla
        $tabla = $objeto->nombre;
        $objeto->alias = "";

        // obtenemos los campos y los valores
        $campos  = [];
        $valores = [];
        foreach ($objeto->campos as $id => $valor) {
            if ($valor !== "") {
                $campos[] = $objeto->key($id);
                if ($valor[0] != "(") $valores[] = "'$valor'";
                else $valores[] = $valor;
            }
        }
        $campos = implode(",",$campos);
        $valores = implode(",",$valores);

        $sql = "insert into $tabla ($campos) values ($valores)";
        if ($conexion != null) {
            if (get_class($conexion) == "Conexion") {

                // debemos obtener solo la consulta del objeto por claves primarias
                $query_obj = new tabla($objeto->id);
                foreach ($objeto->primarias as $pid) {
                    foreach ($objeto->campos as $id => $valor) {
                        if ($pid == $id) {
                            $query_obj->set($id,$valor);
                        }
                    }
                }
                // si la consulta tiene todas las claves primarias definidas
                if (count($query_obj->campos) == count($objeto->primarias)) {
                    $resultado = orm::find($query_obj,[
                        "conexion" => $conexion,
                        "cabeceras" => false
                    ]);
                    if ($resultado == null) {
                        return $conexion->sql_execute($sql);
                    } else {
                        return null;
                    }
                } else {
                    return false;
                }
            }
        }
        return $sql;
    }

    // modificar un registro en la base de datos
    public static function update(tabla $objeto, Conexion $conexion = null)
    {
        // se obtiene el nombre de la tabla
        $tabla = $objeto->nombre;
        $alias = $objeto->alias;

        // obtenemos la condicion y el set
        $condicion = [];
        $valores = [];
        foreach ($objeto->primarias as $pid) {
            foreach ($objeto->campos as $id => $valor) {
                if ($valor !== "") {
                    $campo = $objeto->key($id);
                    if ($pid == $id) {
                        if ($valor[0] != "(") $condicion[] = "$campo = '$valor'";
                        else $condicion[] = "$campo = $valor"; 
                    } else {
                        if ($valor[0] != "(") $valores[] = "$campo = '$valor'";
                        else $valores[] = "$campo = $valor"; 
                    }
                }  
            }
        }

        $condicion = implode(" and ",$condicion);
        $valores = implode(",",$valores);

        // sentencia sql
        $sql = "update $tabla $alias set $valores where $condicion";
        if ($conexion != null) {
            if (get_class($conexion) == "Conexion") {
                return $conexion->sql_execute($sql);
            }
        }
        return $sql;
    }

    // eliminar un registro en la base de datos
    public static function delete(tabla $objeto, Conexion $conexion = null)
    {
        // se obtiene el nombre de la tabla
        $tabla = $objeto->nombre;
        $alias = $objeto->alias;
        $objeto->alias = "";

        // obtenemos la condicion y el set
        $condicion = [];
        foreach ($objeto->campos as $id => $valor) {
            if ($valor !== "") {
                $campo = $objeto->key($id);
                if ($valor[0] != "(") $condicion[] = "$campo = '$valor'";
                else $condicion[] = "$campo = $valor"; 
            }
        }
        $condicion = implode(" and ",$condicion);

        // sentencia sql
        $sql = "delete from $tabla where $condicion";

        // si existe una conexion
        if ($conexion != null) 
        {
            if (get_class($conexion) == "Conexion") 
            {
                // el objeto tiene dependencias?
                if ($objeto->dependencias != null)
                {
                    // obtenemos los campos de dependencia
                    $dep_keys = array_keys($objeto->dependencias);

                    // seleccionamos todos los registros que se van a eliminar
                    $objeto->alias = $alias;
                    $registros = orm::find($objeto,$dep_keys,[
                        "conexion" => $conexion,
                        "cabeceras" => false,
                        "formato" => false
                    ]);

                    // recorremos los registros
                    $dependencias = [];
                    foreach ($registros as $registro)
                    {
                        // obtenemos los objetos de consulta
                        $obj_consulta = [];
                        foreach ($registro as $did => $dvalor)
                        {
                            foreach ($objeto->dependencias[$did] as $ind_tab => $ind_key) {
                                $obj_consulta[$ind_tab][$ind_key] = $dvalor;
                            }
                        }
                        // se realizan las consultas por cada objeto
                        foreach ($obj_consulta as $ind_tab => $ind_arr) {
                            $obj = new tabla($ind_tab);
                            foreach ($ind_arr as $ind_key => $dep_val) {
                                $obj->set($ind_key, $dep_val);
                            }
                            $resultado = orm::find($obj,[
                                "conexion" => $conexion,
                                "cabeceras" => false
                            ]);
                            if ($resultado != null) {
                                if (!in_array($ind_tab, $dependencias)) {
                                    $dependencias[] = $ind_tab;
                                }
                            }
                        }
                    }
                    if (count($dependencias) > 0) {
                        return $dependencias;
                    }
                    else {
                        return $conexion->sql_execute($sql);
                    }
                } else {
                    return $conexion->sql_execute($sql);
                }
            }
        }
        return $sql;
    }
}