<?php
class SociaModelo extends Modelo {
    
    function __construct() {
        parent::__construct();
    }
    
    // consultar una socia en particular
    function datos($rut) 
    {
        return orm::find(orm::socia([
            orm::socia_rut => $rut
        ]),[
            "conexion" => $this->db,
            "objeto" => true
        ]);
    }
    
    // consultar si una socia existe en la bd
    function existe($rut) 
    {    
        // se consulta una socia
        $resultado = $this->datos($rut);
        
        // si no es nulo se retorna verdadero
        if ($resultado != null) return true;
        else return false;
 
    }

    // para ver si una socia ha sido eliminada
    function eliminada($rut) 
    {
        // creamos una socia de consulta
        $resultado = orm::find(orm::socia([
            orm::socia_rut => $rut,
            orm::socia_estado => 3
        ]),$this->db);
        if ($resultado != null) return true;
        else return false;
    }

    // obtener el perfil
    function obtener_perfil($rut) {
        return json_encode(orm::find(orm::socia([
            orm::socia_rut => $rut
        ]),[
            "conexion" => $this->db,
            "cabeceras" => false
        ])[0]);
    }

    // obtener sólo el nombre de una socia
    function obtener_nombre($rut) {
        return orm::select([
            "tablas" => orm::socia([
                orm::socia_rut => $rut
            ]),
            "campos" => [
                orm::concat(
                    orm::socia_nombre(),
                    orm::socia_paterno(),
                    orm::socia_materno()
                )
            ],
            "salida" => [
                "conexion" => $this->db,
                "cabeceras" => false
            ]
        ])[0][0];
    }

    // obtener todas las socias activas
    function listar($permiso,$estado) {

        // se selecciona el estado de la socia
        if ($estado == 3) $opr = "=";
        else $opr = "!=";

        $campos = [
            orm::socia_rut,
            orm::socia_nombre,
            orm::socia_paterno,
            orm::socia_materno,
            orm::socia_fono,
            orm::socia_direccion,
            orm::socia_email,
            orm::socia_permiso
        ];
        switch ($permiso)
        {
            case -1:
            case  0:
            case  1:
                $campos[] = orm::socia_ingreso;
                $campos[] = orm::socia_eliminacion;
                break;
            case  2:
                $campos[] = orm::socia_ingreso;
                break;
        }

        return orm::select([
            "tablas" => orm::socia,
            "campos" => $campos,
            "where" => orm::and(
                orm::socia_estado()." $opr '3'",
                orm::socia_permiso()." != '-1'"
            ),
            "orden" => [
                orm::socia_rut => "asc"
            ],
            "salida" => [
                "conexion" => $this->db,
                "json" => true
            ]
        ]);
    }
    
    // agrega una nueva socia
    function agregar($socia)
    {
        // agregamos una socia a la base de datos
        return orm::insert($socia,$this->db);
    }

    // modificar los datos de una socia
    function modificar($socia)
    {
        // modificamos una socia en la base de datos
        return orm::update($socia,$this->db);
    }

    function eliminar_logicamente($rut,$fecha)
    {
        // eliminamos logicamente una socia a la base de datos
        return orm::update(orm::socia([
            orm::socia_rut => $rut,
            orm::socia_eliminacion => $fecha,
            orm::socia_estado => 3
        ]),$this->db);
    }
    
    function eliminar($rut)
    {
        // eliminamos una socia de la base de datos
        return orm::delete(orm::socia([
            orm::socia_rut => $rut
        ]),$this->db);
    }
    
    // consultar la tabla de permisos
    function lista_permisos($permiso)
    {
        return orm::select([
            "tablas" => orm::socia,
            "campos" => [
                orm::socia_rut,
                orm::socia_nombre,
                orm::socia_paterno,
                orm::socia_materno,
                orm::socia_permiso
            ],
            "where" => orm::and(
                orm::socia_estado()." != 3",
                orm::socia_permiso()." >= ".$permiso,
                orm::socia_permiso()." != -1"
            ),
            "orden" => [
                orm::socia_permiso => "asc"
            ],
            "salida" => [
                "conexion" => $this->db,
                "formato" => [
                    orm::socia_rut_head() => true
                ],
                "json" => true
            ]
        ]);
    }
    
    // gestionar los permisos
    function gestionar_permisos($tabla)
    {
        // se recorre la tabla
        $resultado = true;
        foreach ($tabla as $registro)
        {
            $resultado = ($resultado && orm::update(orm::socia([
                orm::socia_rut => $registro[0],
                orm::socia_permiso => $registro[1]
            ]),$this->db));
        }
        return $resultado;
    }
    
    // lista simple de socias para las vistas 
    function lista_consulta()
    {
        return orm::select([
            "tablas" => orm::socia,
            "campos" => [
                orm::socia_rut,
                orm::socia_nombre,
                orm::socia_paterno,
                orm::socia_materno
            ],
            "where" => orm::and(
                orm::socia_estado()." != '3'",
                orm::socia_permiso()." != '-1'"
            ),
            "orden" => [
                orm::socia_rut => "asc"
            ],
            "salida" => [
                "conexion" => $this->db,
                "json" => true
            ]
        ]);
    }

    // lista simple de socias para las vistas 
    function lista_consulta2($rut)
    {
        return orm::select([
            "tablas" => orm::socia,
            "campos" => [
                orm::socia_rut,
                "Nombre Completo" => orm::concat(
                    orm::socia_paterno(),
                    orm::socia_materno(),
                    orm::socia_nombre()
                )
            ],
            "where" => orm::and(
                orm::socia_estado()." != '3'",
                orm::socia_rut()." != '$rut'",
                orm::socia_permiso()." != '-1'"
            ),
            "orden" => [
                orm::socia_paterno => "asc"
            ],
            "salida" => [
                "conexion" => $this->db,
                "json" => true
            ]
        ]);
    }
}
