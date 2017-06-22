<?php if (isset($_POST["llave"])): ?>
<?php $tabla_ingreso = json_decode($_POST["tablaingreso"]); ?>
<?php $tabla_egreso = json_decode($_POST["tablaegreso"]); ?>
<?php $año  = $_POST["año"] ?>
<?php $arreglo = json_decode($_POST["aa"]);
      $egreso = $arreglo[0][1];
      $total = $arreglo[0][2];
      $ingreso = $arreglo[0][0];
?>      
    <div id="vista_header">
        <p>Cuadrar Caja</p>
    </div>
    <div id="vista_contenido">
    <div class="container-fluid formulario">
       <div class="row">
           <div class="col-md-3 form-group">
                <label for="año" class="control-label">Año</label>
                <div class="input-group">
                    <input id="año" 
                        type="number" 
                        class="form-control" 
                        placeholder="Ingrese Año" 
                        value="<?php echo $año ?>" 
                        style="min-width: 200px;"/>
                    <span class="input-group-btn">
                        <button id="año-confirmar" class="btn btn-default">Confirmar</button>
                    </span>
                </div>
            </div>    
            <div class="col-md-3">
                <label for="ingresos" class="control-label">Ingresos</label>
                <div class="input-group">
                    <span class="input-group-addon">$</span> 
                    <input id="ingresos" type="text" class="form-control" readonly value="<?php echo $ingreso; ?>">
                </div>
            </div>
            <div class="col-md-3">
                <label for="egresos" class="control-label">Egresos</label>
                <div class="input-group">
                    <span class="input-group-addon">$</span> 
                    <input id="egresos" type="text" class="form-control" readonly value="<?php echo $egreso; ?>">
                </div>
            </div>
            <div class="col-md-3">
                <label for="total" class="control-label">Total de dinero existente en la caja</label>
                <div class="input-group">
                    <span class="input-group-addon">$</span> 
                    <input id="total" type="text" class="form-control" readonly value="<?php echo $total; ?>">
                </div>
            </div>
        </div>
    </div>  
    <table id="tabla_ingreso" class="cell-border display" width="100%" cellspacing="0">
       <?php if(count($tabla_ingreso) > 0){ ?>
       <thead>
            <tr> 
                <th colspan="4">INGRESOS</th>
            </tr>
            <?php
                echo "<tr>";
                foreach ($tabla_ingreso[0] as $valor) {
                    echo "<th>";
                    echo $valor;
                    echo "</th>";
                }
                echo "</tr>";
            ?>
        </thead>
        <tbody>
        <?php
        foreach ($tabla_ingreso as $row){
            if ($row != $tabla_ingreso[0]){
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
    <table id="tabla_egreso" class="cell-border display" width="100%" cellspacing="0">
       <?php if(count($tabla_egreso) > 0){ ?>
       <thead>
            <tr> 
                <th colspan="4">EGRESOS</th>
            </tr>
            <?php
                echo "<tr>";
                foreach ($tabla_egreso[0] as $valor) {
                    echo "<th>";
                    echo $valor;
                    echo "</th>";
                }
                echo "</tr>";
            ?>
        </thead>
        <tbody>
        <?php
        foreach ($tabla_egreso as $row){
            if ($row != $tabla_egreso[0]){
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
<div id="vista_footer">
    <div id="vista_confirmar">
        <button class="vista_boton" id="Generar" title="Generar Informe">
            Generar Informe
        </button>
    </div>
</div>
    
<script> var tabla_ingreso = JSON.parse('<?php echo $_POST["tablaingreso"]; ?>'); </script>  
<script> var tabla_egreso = JSON.parse('<?php echo $_POST["tablaegreso"]; ?>'); </script>
<script> var año_actual = <?php echo $_POST["año"]; ?>;</script>
<?php endif ?>