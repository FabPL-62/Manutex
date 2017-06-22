<?php
class TransaccionModelo extends Modelo {
    
    function __construct() {
        parent::__construct();
    }

    // obtener todas las transacciones
    function listar() 
    {    
        return orm::find(orm::transaccion,[
            "conexion" => $this->db,
            "json" => true
        ]);
    }

    // ultima transaccion
    function ultima() {
        $sql = "select max(num_trans) from transaccion";
        $resultado = $this->db->sql_query($sql);
        if ($resultado != null) {
            return array_values($resultado[0])[0];
        }
        return 0;
    }
    
    // agregar una nueva transacción
    function agregar($trans) {
        return orm::insert($trans, $this->db);
    }

    function modificar($trans){
        return orm::update($trans, $this->db);
    }

    function ingresos($año)
    {
        return orm::select([
            "tablas" => orm::transaccion,
            "where" => orm::and(
                orm::transaccion_monto()." > '0'",
                orm::transaccion_fecha()." like '$año%'"
            ),
            "salida" => [
                "conexion" => $this->db,
                "json" => true
            ]
        ]);
    }

    function egresos($año)
    {
        return orm::select([
            "tablas" => orm::transaccion,
            "where" => orm::and(
                orm::transaccion_monto()." < '0'",
                orm::transaccion_fecha()." like '$año%'"
            ),
            "salida" => [
                "conexion" => $this->db,
                "json" => true
            ]
        ]);
    }

    function cuadrar_caja($año)
    {
        return orm::select([
            "tablas" => orm::transaccion(0),
            "campos" => [
                "ingreso" => orm::select([
                    "tablas" => orm::transaccion(1),
                    "campos" => [
                        orm::sum(orm::transaccion_monto(1))
                    ],
                    "where" => orm::and(
                        orm::transaccion_monto(1)." > '0'",
                        orm::transaccion_fecha(1)." like '$año%'"
                    )
                ]),
                "egreso" => orm::select([
                    "tablas" => orm::transaccion(1),
                    "campos" => [
                        orm::sum(orm::transaccion_monto(1))
                    ],
                    "where" => orm::and(
                        orm::transaccion_monto(1)." < '0'",
                        orm::transaccion_fecha(1)." like '$año%'"
                    )
                ]),
                "total" => orm::sum(orm::transaccion_monto(0))
            ],
            "where" => orm::transaccion_fecha(0)." like '$año%'"
            "salida" => [
                "conexion" => $this->db,
                "json" => true
            ]
        ]);
    }

    function cuotas($año)
    {
        return orm::find(orm::cuota([
            orm::cuota_año => $año
        ]),[
            "conexion" => $this->db,
            "json" => true
        ])
    }

    function agregar_valor_anual($val_cuota)
    {
        $resultado = orm::insert($val_cuota,$this->db);
        if ($resultado == true) {
            $resultado = orm::find(orm::socia,[orm::socia_rut],[
                "conexion" => $this->db,
                "cabeceras" => false
            ]);
            foreach ($resultado as $i => $registro) {
                $resultado[$i] = $registro[0];
            }
            foreach ($resultado as $rut) {
                for ($i=0; $i <=10; $i++) {
                    orm::insert(orm::cuota([
                        orm::cuota_año => $val_cuota->get(orm::valor_cuota_año),
                        orm::cuota_numero => $i,
                        orm::cuota_rut => $rut
                    ]),$this->db);
                }
            }
        }
        return json_encode($resultado);
    }

    function modificar_valor_anual($val_cuota) {
        return orm::update($val_cuota,$this->db);
    }

    function consultar_valor_anual($año){
        return orm::find(orm::valor_cuota([
            orm::valor_cuota_año => $año
        ]),[
            "conexion" => $this->db,
            "cabeceras" => false,
            "json" => true
        ]);
    }

    function listar_cuotas($año){
        $resultado = orm::select([
            "tablas" => [orm::socia,orm::cuota([
                orm::cuota_año => $año
            ])],
            "campos" => [
                orm::socia_rut_def(),
                "Nombre Completo" => orm::concat(
                    orm::socia_paterno(),
                    orm::socia_materno(),
                    orm::socia_nombre()
                ),
                orm::cuota_numero_def(),
                orm::cuota_fecha_def()
            ],
            "where" => orm::socia_estado()." != '3'",
            "orden" => [
                orm::socia_rut() => "asc"
            ],
            "salida" => $this->db
        ]);
        $resultado[0] = [
            "Rut",
            "Nombre completo",
            "Ingreso",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre"
        ];
        return json_encode($resultado);
    }

    function pagar_cuotas(tabla $cuota, $meses) {
        $resultado = true;
        foreach ($meses as $mes) {
            $c = $cuota;
            $c->set(orm::cuota_numero,$mes);
            $resultado = ($resultado && orm::update($c,$this->db));
        }
        return $resultado;
    }
}