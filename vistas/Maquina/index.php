<?php if (isset($_POST["llave"])): ?>
<?php 
    $tabla = json_decode($_POST["tabla"]);
    $tipos = json_decode($_POST["tipos"]);
    Vista::sub_head("Máquinas:Agregar",[
        "Eliminar" => ["id" => "MantEliminar", "title" => "Elimine una máquina"],
        "Consultar" => ["id" => "MantConsultar", "title" => "Consulte máquinas"],
        "Modificar" => ["id" => "MantModificar", "title" => "Modifique una máquina existente"],
        "Agregar" => ["id" => "MantAgregar", "title" => "Agregue una nueva Máquina"],
    ]);
?>
<div id="vista_contenido">
    <div class="container-fluid formulario">
        <div class="row fila-general">
            <div class="col-md-3 form-group">
                <label class="control-label" for="maq_tipo">Tipo Máquina</label><br>
                <select class="form-control" id="maq_tipo">
                    <?php
                        foreach ($tipos as $valor) {
                            echo "<option value='".$valor[0]."'>";
                            echo $valor[1];
                            echo "</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="col-md-3 form-group">
                <label class="control-label" for="maq_num">Numero Máquina</label><br>
                <input class="form-control" style="cursor:default;" type="text" id="maq_num" value="<?php echo $tipos[0][2]+1; ?>" disabled="true">
            </div>
            <div class="col-md-3 form-group">
                <label class="control-label" for="maq_ingreso">Fecha de Ingreso</label><br>
                <input class="form-control" input="text" id="maq_ingreso">
            </div>
            <div class="col-md-3 form-group">
                <label class="control-label" for="maq_mantecion">Fecha de Mantención</label><br>
                 <input class="form-control" input="text" id="maq_mantencion" placeholder="Fecha de ultima mantención">
            </div>
        </div>
        <div class="row fila-general">
            <div class="col-md-3 form-group">
               <label class="control-label" for="maq_modelo">Modelo<b>(*)</b></label><br>
               <input class="form-control" type="text" id="maq_modelo" placeholder="Ingrese Modelo de Máquina">
            </div>
            <div class="col-md-3 form-group">
               <label class="control-label" for="maq_marca">Marca<b>(*)</b></label><br>
               <input class="form-control" type="text" id="maq_marca" placeholder="Ingrese Marca de la Máquina">
            </div>
            <div class="col-md-3 form-group">
                <label class="control-label" for="maq_estado">Estado</label><br>
                <select class="form-control" name="" id="maq_estado">
                <option value="O">Operativa</option>
                <option value="D">Defectuosa</option>
                <option value="M">En Mantención</option>
               </select>
            </div>
            <div class="col-md-3 form-group">
                <label class="control-label" for="maq_uso">Cantidad de Uso</label><br>
                <input class="form-control" style="cursor:default;" type="text" id="maq_uso" value="0" disabled="true">
            </div>
        </div>
        <div class="row fila-consulta" hidden>
            <div class="col-md-7">
                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                        <span class="glyphicon glyphicon-eye-open"></span>
                        Visibilidad Columnas
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" style="position:fixed;width: 250px;">
                        <?php foreach ($tabla[0] as $i => $valor) { ?>
                            <?php if ($i >= 2) { ?>
                                <li>
                                <a style="cursor: pointer;" columna="<?php echo $i; ?>">
                                    <div class="row">
                                        <div class="col-md-10 vis-opcion"><?php echo $valor; ?></div>
                                        <span class="col-md-2 glyphicon glyphicon-eye-open" style="right: 10px;"></span>
                                    </div>
                                </a></li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                </div> 
            </div>
            <div class="col-md-3" style="margin-top: 6px;width: 245px;">
                <label class="control-label" for="orientacion">Orientación de la página</label>
            </div>
            <div class="col-md-2 form-group">
                <select class="form-control" id="orientacion">
                    <option value="0">Vertical</option>
                    <option value="1">Horizontal</option>
                </select>
            </div>
        </div>
    </div>
    <?php Vista::sub_table($tabla); ?>
</div>
<?php 
    Vista::sub_foot("Complete todos los campos requeridos <b>(*)</b>",[
        "Confirmar" => ["id" => "MantConfirmar", "title" => "Confirmar la operación"]
    ]); 
?>
<script>
    var tipos_post = JSON.parse('<?php echo $_POST["tipos"]; ?>');
    var tabla_headers = JSON.parse('<?php echo json_encode($tabla[0]); ?>');
</script>
<?php endif ?>