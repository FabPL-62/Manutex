<?php if (isset($_POST["llave"])): ?>
<?php $tabla = json_decode($_POST["tabla"]) ?>
<div id="vista_header" class="container-fluid">
    <div class="row">
        <div class="col-md-7 col-sm-4 col-xs-12">
            <p>Solicitar Máquinas : <span>Agregar</span></p>
        </div>
        <div id="vista_opciones" class="col-md-5 col-sm-8 col-xs-12">
            <button class="vista_boton pull-right" id="MantEliminar" title="Elimine una solicitud">Eliminar</button>
            <button class="vista_boton pull-right" id="MantModificar" title="Modifique una solicitud">Modificar</button>
            <button class="vista_boton pull-right" id="MantAgregar" title="Agregue una nueva solicitud">Agregar</button>
        </div>
    </div> 
</div>
<div id="vista_contenido">
    <div class="container-fluid formulario" style="height: 100px;">
       <div class="row">
           <div class="col-md-3 form-group">
                <label class="control-label" for="fecha">Fecha Solicitud <b>(*)</b></label>
                <input class="form-control" input="text" id="fecha" placeholder="Ingrese Fecha de Solicitud">
           </div>
           <div class="col-md-9" id="tipo-group" hidden>
                <div class="dropdown">
                    <br/>
                    <div class="btn-group" role="group">
                        <div class="btn-group" role="group">
                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" 
                                style="margin-top: 3px;">
                                Máquinas Disponibles &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
                                <span class="tag">0</span>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" style="position:fixed;width: 351px;">
                            </ul>
                        </div>
                        <button type="button" class="btn btn-secondary" id="tipo-select"></button>
                        <div id="tipo-columna" hidden></div>
                    </div>
                </div> 
           </div>
           <!-- <div class="col-md-3"></div> -->
       </div>
    </div>
    <div id="tabla-content" hidden>
      <table id="tabla" class="cell-border hover" width="100%" cellspacing="0">
          <thead>
              <tr>
                <th>Código</th>
                <th>Número</th>
                <th>Fecha de solicitud</th>
                <th>Estado</th>
                <th>Turno</th>
              </tr>
          </thead>
          <?php if(count($tabla) > 0){ ?>
          <tbody>
          <?php
          foreach ($tabla as $row){
            echo "<tr>";
            foreach ($row as $valor) {
                echo "<td>";
                echo $valor;
                echo "</td>";
            }
            echo "</tr>";
          }
          ?>
          </tbody>
          <?php }?>
      </table>
    </div>  
</div>
<?php Vista::sub_foot("Seleccione una fecha de solicitud",[
  "Confirmar" => ["id" => "Confirmar", "title" => "Confirmar la operación", "hidden" => ""]
]); ?>
<?php endif ?>