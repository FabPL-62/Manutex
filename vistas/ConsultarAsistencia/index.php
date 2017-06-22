<?php if (isset($_POST["llave"])): ?>
<?php $tabla = json_decode($_POST["tabla"])[1] ?>
<div id="vista_header">
    <p>Revisar asistencia año <span class="año_actual"><?php echo $_POST['año']; ?></span></p>
</div>
<div id="vista_contenido">
    <div class="container-fluid formulario" style="height: 90px;">
        <div class="row">
            <div class="col-md-3 form-group">
                <label for="año-asistencia" class="control-label">Año</label>
                <div class="input-group">
                    <input id="año-asistencia" 
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
        </div>
    </div>
    <div id="tabla-content" hidden>
        <table id="tabla" class="cell-border hover" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Tipo Evento</th>
                    <th>Fecha Evento</th>
                    <th>Asistencia</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if (is_array($tabla)) {
                foreach ($tabla as $row){
                    echo "<tr>";
                    foreach ($row as $valor) {
                        echo "<td>";
                        if ($valor != $row[2]) echo $valor;
                        else {
                            if ($valor == "1") {
                                echo "<span class='glyphicon glyphicon-ok'></span>";
                            } else {
                                echo "<span class='glyphicon glyphicon-remove'></span>";
                            }
                        }
                        echo "</td>";
                    }
                    echo "</tr>";
                }
            }
            ?>
            </tbody>
        </table>
    </div> 
</div>
<?php 
    // se obtiene el porcentaje de asistencia
    $p = 0;
    if (is_array($tabla)) {
        if (count($tabla) > 0) {
            $t = count($tabla);
            $c = 0;
            foreach($tabla as $row) {
                if ($row[2] == "1") $c++;
            }
            $p = round($c/$t*100);
        }
    }
    Vista::sub_foot("Asistencia total : <span>$p%</span>",[
        "Generar Informe" => [
            "id" => "Generar",
            "title" => "Generar Informe"
        ]
    ]);
?>
<script>
    var año_actual = <?php echo $_POST["año"]; ?>;
    var tabla_actual = JSON.parse('<?php echo $_POST["tabla"]; ?>')[1];
    if (Array.isArray(tabla_actual)) tabla_actual.splice(0,1);
    var nombre = '<?php echo $_POST["nombre"]; ?>';
</script>
<?php endif ?>