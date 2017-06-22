<!-- 
    Pagina Principal : 
    Todas las vistas se incluyen dinámicamente en el contenido de esta pagina
-->
<?php if (isset($_POST["login_rut"])) { ?>
<!DOCTYPE HTML>
<html>

    <!-- Definición del html -->
    <head>  
        <!-- Se importan los estilos -->
        <link rel="stylesheet" href="<?php echo URL_LOCAL;?>main.css"> 

        <!-- se importa jquery -->
        <script src="<?php echo JQUERY;?>"></script>
        
        <!-- Se importa bootstrap -->
        <link rel="stylesheet" href="<?php echo BOOTSTRAP;?>css/bootstrap.min.css">
        <script src="<?php echo BOOTSTRAP;?>js/bootstrap.min.js"></script>

        <!-- Se importa datatable -->
        <link rel="stylesheet" href="<?php echo DATATABLE;?>datatables.min.css">
        <script src="<?php echo DATATABLE;?>datatables.min.js"></script>
        
        <!-- Se importa daterangepicker y moment -->
        <link rel="stylesheet" href="<?php echo MAIN_CSS;?>daterangepicker.css">
        <script src="<?php echo MAIN_JS;?>daterangepicker.js"></script>  
        <script src="<?php echo MAIN_JS;?>moment.min.js"></script>

        <!-- Se importa pdfmake -->
        <script src="<?php echo PDFMAKE; ?>pdfmake.min.js"></script>
        <script src="<?php echo PDFMAKE; ?>vfs_fonts.js"></script>

        <!-- Para validar rut -->
        <script src="<?php echo MAIN_JS;?>validar_rut.js"></script> 

        <!-- título de la pagina -->
        <title>Pagina Principal</title>
        <meta charset="utf-8">

        <!-- Por problemas con el cache! -->
        <meta http-equiv="Expires" content="0">
        <meta http-equiv="Last-Modified" content="0">
        <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
        <meta http-equiv="Pragma" content="no-cache">
    </head>
    
    <!-- Cuerpo -->
    <body>

        <!-- Modal principal para mensajes y errores -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="glyphicon glyphicon-info-sign"></span> 
                    Mensaje de Alerta
                </h4>
              </div>
              <div class="modal-body">
                ...
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-cancelar" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-warning btn-aceptar" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal de respaldo -->
        <div class="modal fade" id="respaldo-modal" tabindex="-1" role="dialog" aria-labelledby="respaldo-cabecera" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close salir" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="respaldo-cabecera">
                    <span class="glyphicon glyphicon glyphicon-cloud-upload"></span> 
                    <span class="respaldo-titulo">Respaldar base de datos</span>
                </h4>
              </div>
              <div class="modal-body">
                <span class="respaldo-mensaje-def">
                    Si presiona <b>Enviar</b> se realizará un respaldo de toda la base de datos
                    en el correo <b>manutex.respaldo@gmail.com</b>. Deberá esperar a que el proceso
                    termine.
                </span>
                <span class="respaldo-mensaje" hidden>
                    <div class="loader"></div>
                    <span>
                    Generando y subiendo el archivo, espere un momento...
                    </span>
                </span>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-cancelar salir" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-warning btn-aceptar" id="respaldo-enviar">Enviar</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Estructura del Menú principal -->
        <div id="principal_menu">
            <ul>
                <li><a class="acerca_de">
                    <img class ="ima" src="<?php echo MAIN; ?>imagenes/manutexlogo.jpg"/>
                    Agrupacion MANUTEX</a>
                </li>
                <?php if ($_POST["login_pass"] < 3) { ?>
                    <li><a style="cursor:default">
                        <?php 
                            if ($_POST["login_pass"] == -1) { 
                                echo "Administrador";
                            } else if ($_POST["login_pass"] == 0){
                                echo "Presidenta";
                            } else if ($_POST["login_pass"] == 1){
                                echo "Secretaria";
                            } else if ($_POST["login_pass"] == 2){
                                echo "Tesorera";
                            }
                        ?>
                        </a>
                        <ul>
                            <?php if ($_POST["login_pass"] < 2) { ?>
                                <li><a class="socia_mant">Socias</a>
                                <?php if ($_POST["login_pass"] == 0 || $_POST["login_pass"] == -1) { ?>
                                <ul>
                                    <li><a class="permisos">Gestionar Permisos</a></li>
                                </ul>
                                <?php } ?>
                                </li>
                            <?php } ?>
                            <?php if ($_POST["login_pass"] == 1 || $_POST["login_pass"] == -1) { ?>
                                <li><a class="evento_mant">Eventos</a>
                                    <ul>
                                        <li><a class="asis_mant">Asistencia</a></li>
                                    </ul>
                                </li>
                                <li><a class="maquina_mant">Máquinas</a>
                                    <ul>
                                        <li><a class="tipos_mant">Tipos de Máquina</a></li>
                                    </ul>
                                </li>
                            <?php } ?>
                            <?php if ($_POST["login_pass"] == 2 || $_POST["login_pass"] == -1) { ?>
                                <li><a class="trans_mant">Transacción</a>
                                    <ul>
                                        <li><a class="ingresar_cuotas">Ingresar Cuotas</a></li>
                                        <li><a class="cuadrar">Cuadrar Caja</a></li>
                                    </ul>
                                </li>
                            <?php } ?>
                            <?php if ($_POST["login_pass"] < 2) { ?>
                                <li><a class="sol_aceptar">Aceptar Solicitudes de Máquinas</a></li>
                            <?php } ?>
                            <?php if ($_POST["login_pass"] < 3) { ?>
                                <li><a class="respaldar">Respaldar datos</a></li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>
                <li><a class="perfil_mant">Modificar Perfil</a></li>
                <?php if ($_POST["login_pass"] >= 2) { ?>
                    <li><a class="socia_mant">Consultar Socias</a></li>
                <?php } ?>
                <li><a class="asistencia">Ver Asistencia</a></li>
                <li><a class="cuotas">Cuotas y Deudas</a></li>
                <li><a class="notif_ver">Notificaciones</a></li>
                <li><a class="sol_maq">Solicitar Máquinas</a></li>
            </ul>
        </div>

        <!-- Agrupa La cabecera con la vista -->
        <div id="principal_content">

            <!-- Cabecera de la pagina -->
            <div id="principal_header">
                <!-- Este nivel es para agrupar -->
                <div>
                    <!-- Nombre del socio -->
                    <div id="principal_bienvenido">
                        <a>Sesión de <span><?php echo $_POST["login_nombre"];?></span></a>
                    </div>

                    <!-- Boton para cerrar la sesión -->
                    <div id="principal_cerrar">
                        <a href="<?php echo URL;?>">Cerrar sesión</a>
                    </div>
                </div>
            </div>

            <!-- Vista Actual -->
            <div id="principal_vista">
                
                <!-- Aca se cargan todas las vistas dependiendo 
                de la opcion seleccionada en el menu -->

            </div>
        </div>
    </body>

    <!-- Sección de Scripts -->
    <script>
        var url_global = "<?php echo URL;?>";
        var login_pass = <?php echo $_POST['login_pass'];?>;
        var login_rut = "<?php echo $_POST['login_rut'];?>";
    </script>
    <script src="<?php echo URL_LOCAL?>main.js"></script>
</html>
<?php } ?>