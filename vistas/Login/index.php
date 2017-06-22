<!DOCTYPE html>
<html>
    <head>

        <!-- Se importan los estilos -->
        <link rel="stylesheet" href="<?php echo URL_LOCAL;?>main.css"> 

        <!-- se importa jquery -->
        <script src="<?php echo JQUERY;?>"></script>
        
        <!-- Se importa bootstrap -->
        <link rel="stylesheet" href="<?php echo BOOTSTRAP;?>css/bootstrap.min.css">
        <script src="<?php echo BOOTSTRAP;?>js/bootstrap.min.js"></script>

        <!-- Para validar rut -->
        <script src="<?php echo MAIN_JS;?>validar_rut.js"></script> 

        <title>Inicio de Sesión</title>
        <meta charset="UTF-8">

    </head>
    <body>
        <div class="login-clean">
            <form id="form_login" method="post" action="<?php echo URL; ?>Principal">
                <h2 class="sr-only">Login Form</h2>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="illustration">
                                <img src="<?php echo MAIN; ?>imagenes/manutexlogo.jpg" width="150px"/>
                            </div>
                        </div>
                        <div class="col-md-7">
                            
                            <input type="text" 
                                id="login_rut"
                                name="login_rut" 
                                placeholder="Ingrese RUT" 
                                class="form-control input-lg" 
                                maxlength="10"
                                autofocus />

                            <p class="help-block text-right" style="color: white; font-size: 25px;">Ejemplo : 12345678-9</p>

                            <input type="password" 
                                id="login_pass"
                                name="login_pass" 
                                placeholder="Ingrese Contraseña" 
                                class="form-control input-lg" />

                            <button id="login_button"
                                class="btn btn-primary btn-block btn-lg" 
                                type="submit">
                                Iniciar Sesión
                            </button>

                            <input name="login_nombre"
                                id="login_nombre"
                                type="hidden"/>

                            <input name="login_email"
                                id="login_email"
                                type="hidden"/>
                        </div>
                    </div>
                    <div class="mensaje"><br/></div>
                </div>
            </form>
        </div>
    </body>
    <script>
        var url_global = "<?php echo URL; ?>";
    </script>
    <script src="<?php echo URL_LOCAL;?>main.js"></script>
</html>
