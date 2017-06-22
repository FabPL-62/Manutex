<?php if (isset($_POST["llave"])): ?>
<?php $tabla = json_decode($_POST["tabla"]); ?>
<?php
    Vista::sub_head("Tipo de máquinas:Agregar",[
        "Eliminar" => ["id" => "MantEliminar", "title" => "Elimine un tipo de máquina"],
        "Consultar" => ["id" => "MantConsultar", "title" => "Consulte tipos de máquinas"],
        "Modificar" => ["id" => "MantModificar", "title" => "Modifique una tipo de máquina"],
        "Agregar" => ["id" => "MantAgregar", "title" => "Agregue una nuevo tipo de máquina"]
    ]);  
?>
<div id="vista_contenido">
    <div class="container-fluid formulario" style="height:90px;">
       <div class="row">
           <div class="col-md-4 form-group">
                <label class="control-label" for="tipo">Tipo de máquina<b>(*)</b></label>
                <input type="text" class="form-control" id="tipo" maxlength="3" placeholder="Ingrese Tipo de Máquina">
           </div>
           <div class="col-md-4 form-group">
               <label class="control-label" for="descripcion">Descripción<b>(*)</b></label>
               <input type="text" class="form-control" id="descripcion" placeholder="Ingrese Descripción de Máquina">
           </div>
           <div class="col-md-4 form-group">
               <label class="control-label" for="cantidad">Cantidad</label>
               <input type="text" class="form-control" id="cantidad" disabled="true" value="0">
           </div>
       </div>
    </div>
    <?php Vista::sub_table($tabla); ?>
</div>
<?php 
    Vista::sub_foot("Complete los campos Requeridos<b>(*)</b>",[
        "Confirmar" => ["id" => "MantConfirmar", "title" => "Confirmar operación"]
    ]);
?>
<?php endif ?>