<?php if (isset($_POST["llave"])): ?>
<?php

  // obtenemos los datos de entrada
  $eliminadas = json_decode($_POST["eliminadas"]);
  $tabla = json_decode($_POST["tabla"]);
  $permiso = $_POST["login_pass"];

  // eliminamos las cabeceras para el arreglo de eliminadas
  array_splice($eliminadas,0,1);

  // obtenemos el estado por defecto
  $estado_def = "Agregar";
  $estado_btn = null;
  if ($permiso >= 2) $estado_def = "Consultar";
  else {
    $estado_btn = [
      "Eliminar" => [
        "id" => "MantEliminar",
        "title" => "Elimine a una socia"
      ],
      "Consultar" => [
        "id" => "MantConsultar",
        "title" => "Consulte los datos de una socia"
      ],
      "Modificar" => [
        "id" => "MantModificar",
        "title" => "Modifique los datos de una socia existente"
      ],
      "Agregar" => [
        "id" => "MantAgregar",
        "title" => "Agregue una nueva socia"
      ]
    ];
  }

  // obtenemos la cabecera de la vista
  Vista::sub_head("Socia:".$estado_def,$estado_btn);
?>
<div id="vista_contenido">
  <div class="container-fluid formulario" style="<?php if ($permiso >= 2) echo "height: 60px;";?>">
    <?php if ($permiso < 2) { ?>
    <div class="fila-general">
      <div class="row">
        <div class="col-md-3 form-group">
          <label class="control-label" for="socia_rut">RUT<b>(*)</b></label>
          <br/>
          <div class="input-group">
            <input type="text" 
               id="socia_rut" 
               class="form-control"
               maxlength="10" 
               placeholder="ingrese RUT"
               required="true"
               aria-describedby="add-rut"
               list="socia_rut_list"
               title="Complete este campo. Si desea agregar una socia antigua, presione dos veces para que aparezca la lista completa de socias eliminadas">
            <datalist id="socia_rut_list">
              <?php foreach ($eliminadas as $registro): ?>
                <option value="<?php echo $registro[0]; ?>"></option>
              <?php endforeach ?>
            </datalist>
            <span class="input-group-addon" id="add-rut">12345678-9</span>
          </div>
        </div>
        <div class="col-md-2 form-group">
          <label class="control-label" for="socia_fono">Fono<b>(*)</b></label>
          <br/>
          <input type="text" 
             id="socia_fono"
             class="form-control"
             placeholder="Ingrese Fono"
             required="true">
        </div>
        <div class="col-md-3 form-group">
          <label class="control-label" for="socia_direccion">Dirección<b>(*)</b></label>
          <br/>
          <input type="text" 
             id="socia_direccion" 
             class="form-control"
             placeholder="Ingrese Dirección"
             required="true">
        </div>
        <div class="col-md-2 form-group">
          <label class="control-label" for="socia_ingreso">Fecha Ingreso</label>
          <br/>
          <input type="text" 
             id="socia_ingreso" 
             class="form-control"
             maxlength="10" 
             placeholder="dd/mm/aaaa">
        </div>
        <div class="col-md-2 form-group">
          <label class="control-label" for="socia_permiso">Permiso</label>
          <br/>
          <select class="form-control" id="socia_permiso">
            <option value="3">Socia</option>
            <option value="0">Presidenta</option>
            <option value="1">Secretaria</option>
            <option value="2">Tesorera</option>
          </select>
        </div>
      </div>
      <div class="row">
        <div class="col-md-2 form-group">
          <label class="control-label" for="socia_nombre">Nombre<b>(*)</b></label>
          <br/>
          <input type="text" 
             id="socia_nombre" 
             class="form-control"
             placeholder="Ingrese Nombre" 
             required="true">
        </div>
        <div class="col-md-3 form-group">
          <label class="control-label" for="socia_paterno">Ap.Paterno<b>(*)</b></label>
          <br/>
          <input type="text" 
             id="socia_paterno" 
             class="form-control"
             placeholder="Ingrese Ap.Paterno"
             required="true">
        </div>
        <div class="col-md-3 form-group">
          <label class="control-label" for="socia_materno">Ap.Materno<b>(*)</b></label>
          <br/>
          <input type="text" 
             id="socia_materno"
             class="form-control"
             placeholder="Ingrese Ap.Materno"
             required="true">
        </div>
        <div class="col-md-4 form-group">
          <label class="control-label" for="socia_email">E-mail</label>
          <br/>
          <div class="input-group">
            <input type="text" 
               id="socia_email"
               class="form-control"
               placeholder="Ingrese E-mail"
               aria-describedby="add-email">
            <span class="input-group-addon" id="add-email">xxxx@xxxx.xx</span>
          </div>
        </div>
      </div>
    </div>
    <?php } ?>
    <div class="fila-consulta" <?php if ($permiso < 2) echo "hidden"; ?>>
      <div class="row">
        <?php if ($permiso < 3) { ?>
        <div class="col-md-1" style="margin-top: 6px;width: 70px;">
          <label class="control-label" for="socia_estado">Estado</label>
        </div>
        <div class="col-md-2 form-group">
          <select class="form-control" id="socia_estado">
            <option value="1">Activa</option>
            <option value="3">Eliminada</option>
          </select>
        </div>
        <?php } ?>
        <div class="col-md-<?php 
          if ($permiso < 3) echo 5;
          else echo 7;
        ?>">
          <div class="dropdown">
            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
              <span class="glyphicon glyphicon-eye-open"></span>
              Visibilidad Columnas
              <span class="caret"></span>
            </button>
              <ul class="dropdown-menu" style="position:fixed;width: 250px;">
                <?php foreach ($tabla[0] as $i => $valor) { ?>
                  <?php if ($i >= 4) { ?>
                    <li
                    <?php 
                      if ($permiso < 2) {
                        if ($i == 9) echo " hidden";
                      } 
                    ?>>
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
  </div>
  <?php Vista::sub_table($tabla); ?>
</div>
<?php
  if ($permiso < 2) {
    $mensaje = "Complete todos los campos requeridos <b>(*)</b>";
    $boton = "Confirmar";
    $titulo = "Confirmar la operación";
  } else {
    $mensaje = "Llene los campos para realizar una busqueda";
    $boton = "Generar Informe";
    $titulo = "Generar el informe de eventos";
  }
  Vista::sub_foot($mensaje,[
      $boton => [
        "id" => "MantConfirmar",
        "title" => $titulo
      ]
  ]);
?>
<script>
  var socias = JSON.parse('<?php echo $_POST["tabla"]; ?>');
  var eliminadas = JSON.parse('<?php echo json_encode($eliminadas); ?>');
</script>
<?php endif ?>