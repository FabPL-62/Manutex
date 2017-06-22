<?php if (isset($_POST["llave"])): ?>
<?php $tabla = json_decode($_POST["tabla"]) ?>
<?php $año  = $_POST["año"] ?>
<?php /*
  if ($_POST["valores"] != 0){*/
    $arreglo = explode(",", $_POST["valores"]);
    $ingreso = explode('"',$arreglo[1])[1];
    $mensual = explode('"',$arreglo[2])[1];
  /*}else{
    $ingreso = "";
    $mensual ="";
  }*/     
?>
<script src="<?php echo MAIN_JS;?>daterangepicker.js"></script>  
<script src="<?php echo MAIN_JS;?>moment.min.js"></script>

<!-- Modal para aceptar pago -->
<div class="modal fade" id="modal-pago" tabindex="-1" role="dialog" aria-labelledby="modal-pago-lbl" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="modal-pago-lbl">
            <span class="glyphicon glyphicon glyphicon-usd"></span> 
            Confirmar Pago de cuotas
        </h4>
      </div>
      <div class="modal-body container-fluid">
        <div class="row">
          <div class="col-md-12">
            <label for="pago-socia" class="control-label">Socia</label>
            <div class="input-group">
              <span class="input-group-addon"><span id="pago-socia-rut"></span></span>
              <input id="pago-socia-nom" type="text" class="form-control" readonly>
            </div>
          </div>
        </div>
        <br/>
        <div class="row">
          <div class="col-md-12">
            <label for="pago-fecha" class="control-label">Fecha de pago</label>
            <div class="input-group">
              <input id="pago-fecha" type="text" class="form-control">
              <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
          </div>
        </div>
        <br/>
        <div class="row">
          <div class="col-md-12">
            <label for="pago-cuotas" class="control-label">Cuotas a pagar</label>
            <input id="pago-cuotas" type="text" class="form-control" readonly>
          </div>
        </div>
        <hr/>
        <div class="row">
          <div class="col-md-12">
            <label for="pago-total" class="control-label">Total a pagar</label>
            <div class="input-group">
              <span class="input-group-addon">$</span>
              <input id="pago-total" type="text" class="form-control" readonly  value="">
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-cancelar" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-warning btn-aceptar" data-dismiss="modal">Generar Comprobante</button>
        <button type="button" class="btn btn-warning btn-aceptar" data-dismiss="modal">Confirmar</button>
      </div>
    </div>
  </div>
</div>

<div id="vista_header" class="container-fluid">
    <div class="row">
        <div class="col-md-7 col-sm-4 col-xs-12">
            <p>Ingresar pagos de cuotas</p>
        </div>  
        <div id="vista_opciones" class="col-md-5 col-sm-8 col-xs-12">
            <button class="vista_boton pull-right" id="MantCambiar" title="">Valor cuotas</button>
            <button class="vista_boton pull-right" id="MantPagar" title="">Pagar Coutas</button>
            <!-- <button class="vista_boton pull-right" id="MantAgregar" title="">Agregar</button> -->
        </div>       
</div>
</div>
<div id="vista_contenido">
    <div class="container-fluid formulario" style="height: 100px;">
          <div class="row">
              <div class="col-md-3 form-group">
                  <label for="año" class="control-label">Año</label>
                  <div class="input-group">
                      <input id="año" 
                          type="number" 
                          class="form-control" 
                          placeholder="Ingrese Año" 
                          value="<?php echo $_POST['año']; ?>" 
                          style="min-width: 200px;"/>
                      <span class="input-group-btn">
                          <button id="año-confirmar" class="btn btn-default">Confirmar</button>
                      </span>
                  </div>
              </div>
              <div class="col-md-3 form-group">
                    <label for="valor_mensual" class="control-label">Valor de la cuota Mensual</label>
                    <br>
                    <div class="input-group">
                      <span class="input-group-addon">$</span> 
                      <input type="text" id="valor_mensual" class="form-control" disabled value="<?php echo $mensual;?>">
                    </div>
              </div>
              <div class="col-md-3 form-group">
                   <label for="valor_ingreso" class="control-label">Valor de la cuota de ingreso</label>
                    <br>
                    <div class="input-group">
                      <span class="input-group-addon">$</span> 
                      <input type="text" id="valor_ingreso" class="form-control" disabled value="<?php echo $ingreso;?>">
                    </div>
              </div>
              <div class="col-md-3 container-fluid" style="margin-top: 10px;">
                <div class="row">
                  <div class="col-md-2">
                    <span class="glyphicon glyphicon-stop" style="color:green"></span>
                  </div>
                  <div class="col-md-10">
                    <label for="colorpago">Cuota pagada</label>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-2">
                    <span class="glyphicon glyphicon-stop" style="color:red"></span>
                  </div>
                  <div class="col-md-10">
                    <label for="colordeuda">Cuota impaga</label>
                  </div>
                </div>
              </div>
          </div>
    </div>
    <table id="tabla" class="cell-border hover" width="100%" cellspacing="0">
        <?php if(count($tabla) > 0){ ?>
        <thead>
            <?php
                echo "<tr>";
                foreach ($tabla[0] as $i => $valor) {
                    if ($i >= 2) {
                      echo "<th style='width:97.4px;'>";
                    } else {
                      echo "<th>";
                    }
                    echo $valor;
                    echo "</th>";
                }
                echo "</tr>";
            ?>
        </thead>
        <tfoot>
            <?php
                echo "<tr>";
                foreach ($tabla[0] as $i => $valor) {
                    if ($i < 2) {
                      echo "<th style='padding:0;'>";
                    } else {
                      echo "<th style='width:97.4px;'>";
                    }
                    echo $valor;
                    echo "</th>";
                }
                echo "</tr>";
            ?>
        </tfoot>
        <tbody>
        <?php  
          $rut_actual="";        
          foreach ($tabla as $i => $registro) {
            $mi_rut = $registro[0]; 
            if ($mi_rut != "Rut"){
               if ($rut_actual != $mi_rut) {
                  $rut_actual = $mi_rut;
                  $mi_nombre = $registro[1];
                  if ($i == 0) {
                    echo "<tr>";
                  } else {
                    echo "</tr><tr>";
                  }
                  echo "<td class='tr-rut'>$mi_rut</td>";
                  echo "<td class='tr-nombre'>$mi_nombre</td>";
                } 
                $mi_mes = $registro[2];
                $mi_fecha_pago = $registro[3];
                $rut_sdv = explode("-",$mi_rut)[0];
                echo "<td mes='$mi_mes' style='text-align:center;' class='td-mes'>$mi_fecha_pago</td>";
                if ($rut_actual == $mi_rut && $i == count($tabla)-1) {
                    echo "</tr>";
                }
              }
            }
        ?>
        </tbody>
        <?php }?>
    </table>
</div> 
<div id="vista_footer">
    <p>Ingrese la cantidad y valor de las cuotas de este año</p>
    <div id="vista_confirmar">
        <!-- <button class="vista_boton" id="modificar">Modificar</button> -->
        <button class="vista_boton" 
                id="MantConfirmar" 
                title="Confirmar operación" hidden>Confirmar
        </button>
    </div>
</div>
<?php endif ?>