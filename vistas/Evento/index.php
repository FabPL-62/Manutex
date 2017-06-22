<?php if (isset($_POST["llave"])): ?>
<?php 
    $tabla = json_decode($_POST["tabla"]);
    Vista::sub_head("Eventos:Agregar",[
        "Eliminar" => [
            "id" => "MantEliminar",
            "title" => "Eliminar un evento"
        ],
        "Consultar" => [
            "id" => "MantConsultar",
            "title" => "Consulte los datos de un evento"
        ],
        "Modificar" => [
            "id" => "MantModificar",
            "title" => "Modificar un evento"
        ],
        "Agregar" => [
            "id" => "MantAgregar",
            "title" => "Agregar un nuevo evento"
        ]
    ]);
?>
<div id="vista_contenido">
    <div class="container-fluid formulario" style="height: calc(50% - 50px); overflow-y: hidden;">
        <div class="fila-general">
            <div class="row">
                <div class="col-md-4 form-group">
                    <label class="control-label" for="evento_fecha">Fecha<b>(*)</b></label><br/>
                    <input type="text" id="evento_fecha" class="form-control" placeholder="dd/mm/aaaa">
                </div>
                <div class="col-md-4 form-group">
                    <label class="control-label" for="evento_tipo">Tipo de evento</label><br/>
                    <select class="form-control" id="evento_tipo">
                        <option value="O">Reunión Ordinaria</option>
                        <option value="E">Reunión Extraordinaria</option>
                        <option value="T">Taller</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 form-group">
                    <label for="evento_desc">Descripción</label><br/>
                    <textarea id="evento_desc" 
                        class="form-control"
                        placeholder="Escriba el acta del evento" 
                        style="resize:none; width:100%;"
                        rows="4" 
                        ></textarea>
                </div>
            </div>
        </div>
        <div class="fila-consulta" hidden>
            <div class="col-md-4">
                <label class="control-label" for="rango_fechas">Seleccione rango de fechas</label><br/>
                <input type="text" id="rango_fechas" class="form-control" placeholder="DD/MM/AAAA">
            </div> 
        </div>
    </div>
    <?php
        Vista::sub_table($tabla,[
            "format" => [
                2 => function($in) {
                    return "<div style='max-height:70px;overflow-y:auto;'>".$in."</div>";
                }
            ]
        ]); 
    ?>
</div>
<?php 
    Vista::sub_foot("Complete los campos Requeridos<b>(*)</b>",[
        "Confirmar" => [
            "id" => "MantConfirmar",
            "title" => "Confirmar la operación"
        ]
    ]);
?>
<?php endif ?>