<?php if (isset($_POST["llave"])): ?>
<?php $tabla = json_decode($_POST["tabla"]); ?>
<?php $eventos = json_decode($_POST["eventos"]); ?>
<div id="vista_header" class="container-fluid">
    <div class="row">
        <div class="col-md-7 col-sm-4 col-xs-12">
            <p><span class="vista_nombre">Asistencia</span> : <span class="vista_estado">Ingresar</span></p>
        </div>
        <div id="vista_opciones" class="col-md-5 col-sm-8 col-xs-12">
            <div class="dropdown pull-right" style="display:inline-block;">
                <button class="vista_boton dropdown-toggle" 
                    id="MantConsultar" 
                    title="Consulte sobre la asistencia"
                    data-toggle="dropdown">
                    Consultar
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li><a style="cursor: pointer;" columna="1">Por Evento</a></li>
                    <li><a style="cursor: pointer;" columna="2">Total anual por socias</a></li>
                </ul>
            </div>
            <button class="vista_boton pull-right" id="MantModificar" title="Modifique la asistencia a un evento">Modificar</button>
            <button class="vista_boton pull-right" id="MantAgregar" title="Agregue la asistencia a un evento">Ingresar</button>
        </div>
    </div> 
</div>
<div id="vista_contenido">
    <div class="container-fluid formulario" style="height: 90px;">
       <div class="row general">
            <div class="col-md-9 form-group">
                <label class="control-label" for="fecha">Fecha del evento</label>
                <div class="input-group">
                    <select class="form-control" id="fecha" aria-describedby="add-fecha"
                    <?php 
                        if (count($eventos) == 0) echo "disabled";
                    ?>>
                        <option value="" disabled selected hidden>Seleccione un evento</option>
                        <?php
                            foreach ($eventos as $valor) {
                                echo "<option value='".$valor[1]."'>";
                                echo $valor[0]." : ".$valor[1];
                                echo "</option>";
                            }
                        ?>
                    </select>
                    <span class="input-group-addon" id="add-fecha" aria-describedby="add-fecha2"><b>O</b>:Reunión Ordinaria</span>
                    <span class="input-group-addon" id="add-fecha2" aria-describedby="add-fecha3"><b>E</b>:Reunión Extraordinaria</span>
                    <span class="input-group-addon" id="add-fecha3"><b>T</b>:Taller</span>
                </div>
            </div>
            <div class="col-md-3 form-group">
               <button id="seleccionar" class="vista_boton pull-right" 
                style="margin-top: 10px;
                    min-width: 120px;
                    max-width: 120px;
                    min-height: 55px;
                    max-height: 55px;
                " hidden>Seleccionar todas</button>
            </div>
       </div>
        <div class="row consulta-anual" hidden>
            <div class="col-md-3 form-group">
                <label for="total-año" class="control-label">Año <b>(*)</b></label>
                <div class="input-group">
                    <input id="total-año" type="number" class="form-control" placeholder="Ingrese Año" />
                    <span class="input-group-btn">
                        <button id="total-confirmar" class="btn btn-default">Confirmar</button>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div id="tabla-content" hidden>
        <table id="tabla" class="cell-border hover" width="100%" cellspacing="0">
            <?php if(count($tabla) > 0){ ?>
            <thead>
                <tr>
                <?php
                    foreach ($tabla[0] as $valor) {
                        echo "<th>";
                        echo $valor;
                        echo "</th>";
                    }
                ?>
                    <th>Porcentaje</th>
                    <th>Eventos asistidos</th>
                    <th>Total de eventos</th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($tabla as $i => $row){
                if ($i != 0){
                    echo "<tr>";
                    foreach ($row as $valor) {
                        echo "<td>";
                        echo $valor;
                        echo "</td>";
                    }
                    echo "<td></td>";
                    echo "<td></td>";
                    echo "<td></td>";
                    echo "</tr>";
                }
            }
            ?>
            </tbody>
            <?php }?>
        </table>
    </div>
</div>
<?php
    if (count($eventos) == 0) $mensaje = "No hay eventos sin asistencias";
    else $mensaje = "Seleccione un Evento";
    Vista::sub_foot($mensaje,[
        "Confirmar" => [
            "id" => "MantConfirmar", 
            "title" => "Confirmar la operación",
            "hidden" => ""
        ]
    ])
?>
<?php endif ?>