<?php if (isset($_POST["llave"])): ?>
<?php $año  = $_POST["año"] ?>
<?php $arreglo = json_decode($_POST["caja"]);
      $egreso = str_replace("-","",$arreglo[0][1]);
      $total = $arreglo[0][2];
      $ingreso = $arreglo[0][0];

      $valor = json_decode($_POST["valor"]);
?> 
<div id="vista_header">
    <p>Revisar cuotas y deudas</p>
</div>
<div id="vista_contenido">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-3">
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
      <div class="col-md-3"></div>
      <div class="col-md-3">
        <label for="valor-ingreso" class="control-label">Valor cuota de ingreso</label>
        <div class="input-group">
          <span class="input-group-addon">$</span> 
          <input id="valor-ingreso" type="text" class="form-control" readonly value="<?php echo $valor[0][1] ?>">
        </div>
      </div>
      <div class="col-md-3">
        <label for="valor-mensual" class="control-label">Valor cuota mensual</label>
        <div class="input-group">
          <span class="input-group-addon">$</span> 
          <input id="valor-mensual" type="text" class="form-control" readonly value="<?php echo $valor[0][2] ?>">
        </div>
      </div>
    </div>
    <hr/>
    <div class="row">
      <div class="col-md-6">
        <fieldset>
          <legend>Cuotas mensuales de la socia</legend>
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-12 form-group">
                <label for="cuotas-pagadas" class="control-label">Cuotas pagadas</label>
                <input id="cuotas-pagadas" type="text" class="form-control" readonly value="1">
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 form-group">
                <label for="cuotas-por-pagar" class="control-label">Cuotas por pagar</label>
                <input id="cuotas-por-pagar" type="text" class="form-control" readonly value="9">
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 form-group">
                <label for="cuotas-total" class="control-label">Total pagado</label>
                <div class="input-group">
                  <span class="input-group-addon">$</span> 
                  <input id="cuotas-total" type="text" class="form-control" readonly value="5000">
                </div>
              </div>
            </div>
          </div>
        </fieldset>
      </div>
      <div class="col-md-6">
        <fieldset>
          <legend>Dinero de la caja</legend>
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-12 form-group">
                <label for="trans-ingresos" class="control-label">Ingresos</label>
                <div class="input-group">
                  <span class="input-group-addon">$</span> 
                  <input id="trans-ingresos" type="text" class="form-control" readonly value="<?php echo $ingreso ?>" >
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 form-group">
                <label for="trans-egresos" class="control-label">Egresos</label>
                <div class="input-group">
                  <span class="input-group-addon">$</span> 
                  <input id="trans-egresos" type="text" class="form-control" readonly value="<?php echo $egreso ?>" >
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 form-group">
                <label for="trans-caja" class="control-label">Monto en caja</label>
                <div class="input-group">
                  <span class="input-group-addon">$</span> 
                  <input id="trans-caja" type="text" class="form-control" readonly value="<?php echo $total ?>">
                </div>
              </div>
            </div>
          </div>
        </fieldset>
      </div>
    </div>
  </div>
</div>
<div id="vista_footer">
    <p>Seleccione el año que desea consultar</p>
    <div id="vista_confirmar">
        <button class="vista_boton" 
                id="Confirmar" 
                title="Confirmar operación">Generar informe
        </button>
    </div>
</div>
<?php endif ?>