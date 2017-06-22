<?php if (isset($_POST["llave"])): ?>
<?php 
  $tabla = json_decode($_POST["tabla"]);  
  $ultima = intval($_POST["ultima"])+1;
?>
<div id="vista_header" class="container-fluid">
  <div class="row">
    <div class="col-md-7 col-sm-4 col-xs-12">
      <p>Agregar Transacción : Nº <span id="ultima"><?php echo $ultima; ?></span> </p>
    </div>
    <div id="vista_opciones" class="col-md-5 col-sm-8 col-xs-12">
         <button class="vista_boton pull-right" id="MantConsultar" title="Consultar una transacción">Consultar</button>
         <button class="vista_boton pull-right" id="MantModificar" title="Modificar una transacción">Modificar</button>
         <button class="vista_boton pull-right" id="MantAgregar" title="Agregar a una nueva transacción">Agregar</button>
     </div>
  </div>     
</div>
<div id="vista_contenido">
   <div class="container-fluid formulario">
      <div class="row">
        <div class="col-md-4">
          <div class="row">
            <div class="col-md-6">  
              <label class="control-label" for="trans_fecha">Fecha</label><br>   
              <input class="form-control" input="text" id="trans_fecha">    
            </div>
            <div class="col-md-6">
              <label class="control-label" for="trans_tipo">Tipo</label><br>
              <select class="form-control" id="trans_tipo">
                <option value="1">Ingreso</option>
                <option value="-1">Egreso</option>
              </select>
            </div>
          </div>  
           
          <div class="row"> 
            <div class="col-md-12"> 
              <label class="control-label" for="trans_monto">Monto <b>(*)</b></label><br>
              <div class="input-group">
              <span class="input-group-addon" id="add-monto">$</span>
              <input class="form-control" type="text" id="trans_monto" 
              placeholder="Ingrese Monto" aria-describedby="add-monto">
              </div>
        </div>
       </div> 
      </div>    
      <div class="col-md-8">
        <label class="control-label" for="trans_descrip">Descripción<b>(*)</b></label><br/>
        <textarea id="trans_descrip" class="form-control" rows="3" style="resize:none; width:100%;"
            placeholder="Ingrese una descripción sobre la transacción"></textarea>
      </div>
    </div>
  </div> 
  <div id="tabla-content" hidden>
    <table id="tabla" class="cell-border hover" width="100%" cellspacing="0">
       <?php if(count($tabla) > 0){ ?>
        <thead>
            <?php
                echo "<tr>";
                foreach ($tabla[0] as $valor) {
                    echo "<th>";
                    echo $valor;
                    echo "</th>";
                }
                echo "</tr>";
            ?>
        </thead>
        <tfoot>
            <?php
                echo "<tr>";
                foreach ($tabla[0] as $valor) {
                    echo "<th>";
                    echo $valor;
                    echo "</th>";
                }
                echo "</tr>";
            ?>
        </tfoot>
        <tbody>
        <?php
        foreach ($tabla as $row){
            if ($row != $tabla[0]){
                echo "<tr>";
                foreach ($row as $valor) {
                    echo "<td>";
                    echo $valor;
                    echo "</td>";
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
<div id="vista_footer">
   <p>Complete los campos Requeridos<b>(*)</b></p>
   <div id="vista_confirmar">
       <button class="vista_boton" id="Confirmar" tittle="Confirmar la operación">
          Confirmar
       </button> 
    </div>
</div>
<script>
  var ultima = <?php echo $ultima; ?>;
</script>
<?php endif ?>