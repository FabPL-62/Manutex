<?php if (isset($_POST["llave"])): ?>
<?php $tabla = json_decode($_POST["tabla"]) ?>
<div id="vista_header">
    <p>Modificar perfil</p>
</div>
<div id="vista_contenido">
    <div class="container-fluid">
      <div class="row" style="padding-bottom: 30px;">
        <div class="col-md-3 form-group">
          <label class="control-label" for="rut">Rut</label>
          <input class="form-control" type="text" id="rut" readonly value="<?php echo $tabla[0]; ?>">
        </div>
        <div class="col-md-4 form-group">
          <label class="control-label" for="nombre">Nombre Completo</label>
          <input class="form-control" type="text" id="nombre" readonly 
            value="<?php echo $tabla[1]." ".$tabla[2]." ".$tabla[3]; ?>">
        </div>
        <div class="col-md-3 form-group">
          <label class="control-label" for="fecha">Fecha de Ingreso</label>
          <input class="form-control" type="text" id="fecha" readonly 
            value="<?php echo $tabla[10]; ?>">
        </div>
        <div class="col-md-2 form-group">
          <label class="control-label" for="permiso">Permiso</label>
          <input class="form-control" type="text" id="permiso" readonly 
            value="<?php echo $tabla[8]; ?>">
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <fieldset id="datos_perfil">
            <legend>Datos del Perfil</legend>
            <div class="container-fluid">
              <div class="row">
                <div class="col-md-12 form-group">
                  <label class="control-label" for="fono">Fono/Celular <b>(*)</b></label>
                  <input class="form-control" type="text" id="fono" value="<?php echo $tabla[4]; ?>">
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 form-group">
                  <label class="control-label" for="dir">Dirección <b>(*)</b></label>
                  <input class="form-control" type="text" id="dir" value="<?php echo $tabla[5]; ?>">
                </div>
              </div>
              <div class="row">
                  <div class="col-md-12 form-group">
                    <label class="control-label" for="email">Email</label>
                    <div class="input-group">
                      <input class="form-control" 
                             type="text" 
                             id="email" 
                             value="<?php echo $tabla[6]; ?>"
                             aria-describedby="add-email">
                      <span class="input-group-addon" id="add-email">xxxx@xxxx.xx</span> 
                    </div> 
                  </div>
              </div>
            </div>
          </fieldset>
        </div>
        <div class="col-md-6">
          <fieldset>
            <legend>Modificar Contraseña</legend>
            <div class="container-fluid">
              <div class="row">
                <div class="col-md-12 form-group">
                  <label class="control-label" for="ant">Ingrese antigua contraseña</label>
                  <div class="input-group">
                    <input class="form-control" 
                           type="password" 
                           id="ant"
                           aria-describedby="add-ant"> 
                    <span class="input-group-addon" id="add-ant"></span> 
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 form-group">
                 <label class="control-label" for="nueva">Ingrese nueva contraseña</label>
                 <div class="input-group">
                    <input class="form-control" 
                           type="password" 
                           id="nueva"
                           aria-describedby="add-nueva"> 
                    <span class="input-group-addon" id="add-nueva"></span> 
                 </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 form-group">
                    <label class="control-label" for="nueva2">Confirme contraseña</label>
                    <div class="input-group">
                      <input class="form-control" 
                             type="password" 
                             id="nueva2"
                             aria-describedby="add-nueva2"> 
                      <span class="input-group-addon" id="add-nueva2"></span> 
                    </div>
                </div>
              </div>
            </div>
          </fieldset>
        </div>
      </div> 
   </div>
</div>
<?php Vista::sub_foot("Puede modificar los datos disponibles, recuerde completar los campos requeridos <b>(*)</b>",[
  "Guardar los cambios" => ["id" => "Guardar", "title" => "Guardar los cambios"]
]); ?>
<script>
  var pass = "<?php echo $tabla[9]; ?>";
</script>
<?php endif ?>
