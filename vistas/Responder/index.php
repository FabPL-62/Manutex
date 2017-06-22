<?php if (isset($_POST["llave"])): ?>
<?php $notificacion = json_decode($_POST["notif_contenido"]); ?>
<?php $respuesta = json_decode($_POST["notif_respuesta"]);  ?>
<?php 
  $rut = explode("-", $_POST["rut"]);
  $rut_dg = $rut[0]; 
?>
<?php Vista::sub_head("Notificacion:Responder",[
  "Volver" => ["id" => "notif-nav", "title" => "Volver a notificaciones"]
]); ?>
<div id="vista_contenido">
  <div class="notif-respuestas">
    <div class="notif panel panel-primary" 
         style="font-size:30px;" 
         cod-notif="<?php echo $notificacion[0]; ?>">
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
    </div>
    <?php if ($respuesta !== null): ?>
    <?php foreach($respuesta as $valor_res){ ?>
    <div class="resp panel panel-default" 
         style="font-size:30px;" 
         resp-num="<?php echo $valor_res[2]; ?>">
      <div class="panel-heading container-fluid" style="padding: 10px 15px;">
        <div class="row">
          <div class="col-md-9">
            <b><span class="resp-nom"><?php echo $valor_res[5]?></span></b>
          </div>
          <div class="col-md-3 pull-right" style="text-align: right;">
            <b>Fecha:</b> <span class="resp-fecha"><?php echo $valor_res[3]?></span>
          </div>
        </div>
      </div>
      <div class="panel-body container-fluid" style="text-align: justify;">
        <div class="row">
          <div class="col-md-12 resp-desc text-justify">
            <Textarea disabled style="resize:none;
              width: 100%;
              border: 0;
              background: #fff;"><?php echo $valor_res[4]?></Textarea>
              <input type="hidden" class="resp-guarda" />
          </div>
        </div>
      </div>
      <?php if ($rut_dg == $valor_res[1]) { ?>
      <div class="panel-footer container-fluid resp-opciones">
        <button type="button" class="btn btn-primary pull-right resp-btn-modificar">Modificar</button> 
        <button type="button" class="btn btn-danger pull-right resp-btn-eliminar">Eliminar</button>
      </div>
      <?php } ?>
    </div>
    <?php } ?>
    <?php endif ?>
  </div>
  <div class="mi-resp panel panel-primary" 
       style="font-size:30px;">
    <div class="panel-heading container-fluid" style="padding: 10px 15px;">
      <div class="row">
        <div class="col-md-12">
          <b>Ingrese su respuesta</b>
        </div>
      </div>
    </div>
    <div class="panel-body container-fluid" style="text-align: justify;">
      <div class="row">
        <div class="col-md-12 mi-resp-desc text-justify">
          <Textarea style="resize:none;
            width: 100%;
            border: 0;
            background: #fff;"
            placeholder="Ingrese su respuesta"></Textarea>
        </div>
      </div>
    </div>
    <div class="panel-footer container-fluid resp-opciones">
      <button type="button" class="btn btn-warning pull-right mi-resp-btn-enviar">Enviar</button>
    </div>
  </div>
</div>
<div id="vista_footer">
    <p>Envie una respuesta</p>
</div>
<script>
  <?php if ($respuesta != "") { ?>
    var respuesta = JSON.parse('<?php echo $_POST["notif_respuesta"]; ?>');
  <?php } else { ?>
    var respuesta = null;
  <?php } ?>
  var notificacion = <?php echo $notificacion[0]; ?>; 
  var rut = <?php echo $rut_dg; ?>;
</script>
<?php endif ?>