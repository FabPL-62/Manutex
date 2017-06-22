<?php
require "./libs/Rut.php";
class SociaControl extends Controlador {
    
    function __construct() {
        parent::__construct();
    }
    
    // listar todas las socias segun el permiso del usuario
    function listar()
    {
        if ($this->input("permiso","estado"))
        {
            // obtenemos el permiso
            $permiso = intval($_POST["permiso"]);

            // obtenemos el estado
            $estado = intval($_POST["estado"]);

            // se manda al modelo a obtener toda la lista de socias segun permiso
            echo $this->modelo->listar($permiso,$estado);
        }
    }

    // agregar una nueva socia
    function agregar()
    {
        if ($this->input(
            "socia_rut",
            "socia_nombre",
            "socia_paterno",
            "socia_materno",
            "socia_fono",
            "socia_direccion",
            "socia_email",
            "socia_ingreso",
            "socia_permiso"
        )){
            // obtenemos el rut
            $rut = rut_verificar($_POST["socia_rut"]);

            // verificamos que el rut sea valido
            if ($rut > 0)
            {
                $resultado = $this->modelo->agregar(orm::socia([
                    orm::socia_rut => $rut,
                    orm::socia_nombre => $_POST["socia_nombre"],
                    orm::socia_paterno => $_POST["socia_paterno"],
                    orm::socia_materno => $_POST["socia_materno"],
                    orm::socia_fono => $_POST["socia_fono"],
                    orm::socia_direccion => $_POST["socia_direccion"],
                    orm::socia_email => $_POST["socia_email"],
                    orm::socia_ingreso => $_POST["socia_ingreso"],
                    orm::socia_permiso => $_POST["socia_permiso"],
                    orm::socia_contraseña => $_POST["socia_rut"]
                ]));
                if ($resultado !== null) {
                    if ($resultado == true) {
                        $this->success("La socia se ingresó exitosamente en el sistema");
                    } else {
                        $this->error("La socia no se pudo ingresar al sistema");
                    }
                } else {
                    // si la socia ha sido eliminada
                    if ($this->modelo->eliminada($rut) == true) {
                        $resultado = $this->modelo->modificar(orm::socia([
                            orm::socia_rut => $rut,
                            orm::socia_nombre => $_POST["socia_nombre"],
                            orm::socia_paterno => $_POST["socia_paterno"],
                            orm::socia_materno => $_POST["socia_materno"],
                            orm::socia_fono => $_POST["socia_fono"],
                            orm::socia_direccion => $_POST["socia_direccion"],
                            orm::socia_email => $_POST["socia_email"],
                            orm::socia_ingreso => $_POST["socia_ingreso"],
                            orm::socia_permiso => $_POST["socia_permiso"],
                            orm::socia_estado => 1
                        ]));
                        if ($resultado == true) {
                            $this->success("La socia antigua se agregó exitosamente al sistema");
                        } else {
                            $this->error("La socia no se pudo ingresar al sistema");
                        }
                    } else {
                        $this->error("No puede agregar una socia que ya existe");
                    }
                }
            } else {
                // sino se muestran errores de verificacion del rut //
                $this->error($rut);
            }
        }
    }

    // modificar una socia
    function modificar()
    {
        if ($this->input(
            "socia_rut",
            "socia_nombre",
            "socia_paterno",
            "socia_materno",
            "socia_fono",
            "socia_direccion",
            "socia_email",
            "socia_permiso",
            "socia_ingreso"
        )){
            // separamos el rut por su digito verificador
            $rut = explode("-",$_POST["socia_rut"])[0];

            // llamamos la funcion del modelo
            $resultado = $this->modelo->modificar(orm::socia([
                orm::socia_rut => $rut,
                orm::socia_nombre => $_POST["socia_nombre"],
                orm::socia_paterno => $_POST["socia_paterno"],
                orm::socia_materno => $_POST["socia_materno"],
                orm::socia_fono => $_POST["socia_fono"],
                orm::socia_direccion => $_POST["socia_direccion"],
                orm::socia_email => $_POST["socia_email"],
                orm::socia_permiso => $_POST["socia_permiso"],
                orm::socia_ingreso => $_POST["socia_ingreso"]
            ]));

            // verificamos el resultado
            if ($resultado == true) {
                $this->success("La socia ha sido modificada exitosamente");
            } else {
                $this->error("La socia no se pudo modificar en el sistema");
            }
        }
    }

    // eliminar logicamente a una socia
    function eliminar_logicamente()
    {
        if ($this->input("socia_rut","usuario_rut","fecha_elim"))
        {
            // separamos el rut por su digito verificador
            $rut_socia = explode("-",$_POST["socia_rut"])[0];
            $rut_usuario = explode("-",$_POST["usuario_rut"])[0];
            $fecha_elim = $_POST["fecha_elim"];

            // el rut del usuario debe ser distinto al de la socia que se desea eliminar
            if ($rut_socia != $rut_usuario)
            {
                // llamamos la funcion del modelo
                $resultado = $this->modelo->eliminar_logicamente($rut_socia,$fecha_elim);

                // si el resultado es true
                if ($resultado == true) $this->success("La socia se eliminó exitosamente en el sistema");
                else $this->error("No se pudo eliminar la socia");
            }
            else {
                $this->error("El rut se esta usando actualmente");
            }
        }
    }
    
    // eliminar a una socia
    function eliminar()
    {
        if ($this->input("socia_rut","usuario_rut"))
        {
            // separamos el rut por su digito verificador
            $rut_socia = explode("-",$_POST["socia_rut"])[0];
            $rut_usuario = explode("-",$_POST["usuario_rut"])[0];

            // el rut del usuario debe ser distinto al de la socia que se desea eliminar
            if ($rut_socia != $rut_usuario)
            {
                // obtenemos los datos de las 2 socias
                $socia = $this->modelo->datos($rut_socia);
                $usuario = $this->modelo->datos($rut_usuario);

                // obtenemos sus permisos
                $socia_permiso = intval($socia->get(orm::socia_permiso));
                $usuario_permiso = intval($usuario->get(orm::socia_permiso));

                // solo se puede eliminar si los permisos de la socia son menores
                if ($socia_permiso >= $usuario_permiso)
                {
                    // llamamos la funcion del modelo
                    $resultado = $this->modelo->eliminar($rut_socia);
                    if (is_array($resultado)) {
                        foreach ($resultado as $i => $valor) {
                            switch ($valor)
                            {
                                case orm::transaccion :
                                    $resultado[$i] = "Posee transacciones asociadas a ella";
                                break;
                                case orm::cuota :
                                    $resultado[$i] = "Tiene cuotas impagas";
                                break;
                                case orm::asistencia :
                                    $resultado[$i] = "Tiene asistencias asociadas a ella";
                                break;
                                case orm::notificacion_destinatario :
                                    $resultado[$i] = "Hay notificaciones asociadas a ella";
                                break;
                                case orm::notificacion :
                                    $resultado[$i] = "Ha emitido notificaciones";
                                break;
                                case orm::notificacion_respuesta :
                                    $resultado[$i] = "Ha respondido notificaciones";
                                break;
                                case orm::solicitud_maquina :
                                    $resultado[$i] = "Ha solicitado maquinas";
                                break;
                            }
                        }
                        echo json_encode([2,$resultado]);
                    } else {
                        if ($resultado == true) $this->success("La socia se eliminó exitosamente en el sistema");
                        else $this->error("La socia no se pudo eliminar");
                    }
                } else $this->error("No puede eliminar a una socia de mayor rango");
            } else $this->error("Usted no se puede eliminar a si misma");
        }
    }

    // obtener el perfil de la socia
    function obtener_perfil() {
        if ($this->input("login_rut")) {
            $rut = explode("-",$_POST["login_rut"])[0];
            echo $this->modelo->obtener_perfil($rut);
        }
    }

    // obtener el nombre de la socia
    function obtener_nombre() {
        if ($this->input("login_rut")) {
            $rut = explode("-",$_POST["login_rut"])[0];
            echo $this->modelo->obtener_nombre($rut);
        }
    }
    
    // modificar solo el perfil de una sola socia
    function modificar_perfil()
    {
        if ($this->input(
            "socia_rut",
            "socia_fono",
            "socia_direccion",
            "socia_email",
            "socia_contraseña"
        )){
            // separamos el rut por su digito verificador
            $rut = explode("-",$_POST["socia_rut"])[0];

            $socia = orm::socia([
                orm::socia_rut => $rut,
                orm::socia_fono => $_POST["socia_fono"],
                orm::socia_direccion => $_POST["socia_direccion"],
                orm::socia_email => $_POST["socia_email"]
            ]);

            if ($_POST["socia_contraseña"] != "") {
                $socia->set(orm::socia_contraseña,$_POST["socia_contraseña"]);
            }

            // llamamos la funcion del modelo
            $resultado = $this->modelo->modificar($socia);
            if ($resultado == true) $this->success("Sus datos han sido actualizados exitosamente");
            else $this->error("Sus datos no han sido modificados");
        }
    }

    // obtener listado de socias con los permisos
    // segun el permiso del usuario
    // Admin -> Presidenta -> {Secretaria, Tesorera, Socia}
    function lista_permisos() 
    {
        if ($this->input("usuario_permiso"))
        {
            $permiso = intval($_POST["usuario_permiso"]);
            echo $this->modelo->lista_permisos($permiso);
        }
    }
    
    // gestionar los permisos
    function gestionar_permisos()
    {
        if ($this->input("tabla_permisos"))
        {
            // decodificamos la tabla de permisos
            $tabla = json_decode($_POST["tabla_permisos"]);
            
            // mandamos al modelo a gestionar permisos
            echo $this->modelo->gestionar_permisos($tabla);
        }
    }

    // consultar datos basicos de las socias
    function lista_consultar(){
        if ($this->input()) {
            echo $this->modelo->lista_consulta();
        }
    }

    // consultar datos basicos de las socias
    function lista_consultar2(){
        if ($this->input("rut")) {
            $rut = explode("-",$_POST["rut"])[0];
            echo $this->modelo->lista_consulta2($rut);
        }
    }
} 