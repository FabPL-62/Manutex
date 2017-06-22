<?php if (isset($_POST["llave"])): ?>
<?php 
  $tabla = json_decode($_POST["tabla"]);
  $estado = json_decode($_POST["estado"]);
  $entrada = "";
  if ($estado[0] == "1") {
    $entrada = $estado[1];
  }
?>
<div class="col-md-6" style="height: 100%;padding-top: 10px;overflow: hidden;">
  <div class="form-group row">
    <div class="col-xs-12">
      <input class="form-control form-control-lg" 
        type="text" 
        id="notif-asunto" 
        placeholder="Ingrese Asunto de la Notificación (*)"
        style="font-size: 25px;"
        <?php
          if ($estado[0] == "1") {
            $entrada = $estado[1][2];
            echo "value='$entrada'";
          }
        ?>>
    </div>
  </div>
  <div class="form-group row">
    <div class="col-xs-12">
      <textarea id="notif-desc"
        class="form-control form-control-lg"
        placeholder="Ingrese el contenido de la Notificación (*)" 
        style="resize:none; 
          width:100%;
          font-size: 25px;
          text-align: justify;"
        maxlength="500"
        rows="10"
        ><?php
          if ($estado[0] == "1") {
            echo $estado[1][1];
          }
        ?></textarea>
        <span id="notif-desc-help" class="form-text text-muted" style="font-size: 25px;">
          Puede colocar un máximo de 500 caracteres.
        </span>
    </div>
  </div>
</div>
<div class="col-md-6" style="padding: 0;height: 100%;">
  <div style="overflow-y: auto;height: 100%;">
    <div style="
        width: 100%;
        height:50px;
        background:#EDEDED;
        padding: 10px;">
      <b>
        <span class="glyphicon glyphicon-user"></span> Seleccione a las Destinatarias
      </b>
      <button type="button" 
        class="btn btn-default btn-sel-todo pull-right"
        style="margin-top: -4px;">Seleccionar Todas</button>
    </div>
    <table id="tabla-dest" class="cell-border hover" width="100%" cellspacing="0">
      <?php if(count($tabla) > 0){ ?>
        <thead>
          <?php
              echo "<tr style='background:#E0E0E0;'>";
              foreach ($tabla[0] as $i => $valor) {
                  echo "<th>";
                  echo $valor;
                  echo "</th>";
              }
              echo "</tr>";
          ?>
        </thead>
        <tbody>
        <?php
        foreach ($tabla as $row){
            if ($row != $tabla[0]){
                echo "<tr style='cursor:pointer;' ";
                if (isset($row[2])) {
                  if ($row[2] == "1") {
                    echo "class='selected'";
                  }
                }
                echo ">";
                foreach ($row as $i => $valor) {
                    if ($i != 2) {
                      echo "<td>";
                      echo $valor;
                      echo "</td>";
                    }
                }
                echo "</tr>";
            }
        }
        ?>
        </tbody>
      <?php }?>
    </table>
  </div>
</div>
<script>
  var vista_estado = JSON.parse('<?php echo $_POST["estado"]; ?>');
</script>
<?php endif ?>