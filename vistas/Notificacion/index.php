<?php if (isset($_POST["llave"])): ?>
<?php $tabla = json_decode($_POST["notificacion"]); ?>
<div id="vista_header" class="container-fluid">
  <div class="row">
    <div class="col-md-7 col-sm-4 col-xs-12">
      <p>Notificaciones</p>
    </div>
    <?php if ($_POST["login_pass"] != 3) { ?>
    <div id="vista_opciones" class="col-md-5 col-sm-8 col-xs-12">
      <button class="vista_boton pull-right" id="notif-nav" title="Agregue una nueva notificación">Agregar</button>
    </div>
    <?php } ?>
  </div>
</div>
<div id="vista_contenido">
  <?php if ($tabla != "") { ?>
  <?php if (count($tabla) > 0) { ?>
  <?php foreach ($tabla as $i => $notificacion) { ?>
  <div class="notif panel panel-default" 
       style="font-size:30px;" 
       cod-notif="<?php echo $notificacion[0]; ?>"
       id-array="<?php echo $i; ?>">
    <div class="panel-heading container-fluid" style="padding: 10px 15px;">
      <div class="row">
        <div class="col-md-2">
          <b><span class="notif-permiso"><?php echo $notificacion[5];?></span></b>
        </div>
        <div class="col-md-3 pull-right" style="text-align: right;">
          <b>Fecha:</b> <span class="notif-fecha"><?php echo $notificacion[2];?></span>
        </div>
      </div>
    </div>
    <div class="panel-body" style="text-align: justify;">
      <b>Asunto:</b> <span class="notif-asunto"><?php echo $notificacion[4];?></span>
      <hr style="margin: 10px 0px;"/>
      <span class="notif-desc">
      <?php echo $notificacion[3]; ?>
      </span>
    </div>
    <div class="panel-footer container-fluid notif-opciones">
      <button type="button" class="btn btn-warning pull-right notif-btn-responder">Responder</button> 
      <?php if ($notificacion[1] == explode("-",$_POST["login_rut"])[0]) { ?>
      <button type="button" class="btn btn-primary pull-right notif-btn-modificar">Modificar</button> 
      <button type="button" class="btn btn-danger pull-right notif-btn-eliminar">Eliminar</button>
      <?php } ?>
    </div>
  </div>
  <?php }}} else {
    echo "Usted no tiene notificaciones.";
  } ?>
</div>
<?php Vista::sub_foot("Seleccione una notificación para responder, modificar o eliminar",[
  "Confirmar" => ["id" => "Confirmar", "title" => "Confirmar la operacion", "hidden" => ""]
]); ?>
<script>
  <?php if ($tabla != "") { ?>
    var notificaciones = JSON.parse('<?php echo $_POST["notificacion"]; ?>');
  <?php } else { ?>
    var notificaciones = null;
  <?php } ?>
</script>
<?php endif ?>