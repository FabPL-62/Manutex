<?php if (isset($_POST["llave"])): ?>
<?php $tabla = json_decode($_POST["tabla"]); ?>
<div id="vista_header">
    <p>Gestionar Permisos</p>
</div>
<div id="vista_contenido">
    <div id="tabla-content" hidden>
        <table id="tabla" class="cell-border hover" width="100%" cellspacing="0">
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
            <tbody>
            <?php
            foreach ($tabla as $row){
                if ($row != $tabla[0]){
                    echo "<tr>";
                    foreach ($row as $valor) {
                        echo "<td>";
                        if ($valor != $row[4]) echo $valor;
                        else {
                            echo "<div class='form-group' style='margin-bottom:0px;'>";
                            echo "<select class='form-control permiso'>";
                            echo "<option value='-1' ".(($row[4]=="-1")?"selected":"").">Admin</option>";
                            echo "<option value='0' ".(($row[4]=="0")?"selected":"").">Presidenta</option>";
                            echo "<option value='1' ".(($row[4]=="1")?"selected":"").">Secretaria</option>";
                            echo "<option value='2' ".(($row[4]=="2")?"selected":"").">Tesorera</option>";
                            echo "<option value='3' ".(($row[4]=="3")?"selected":"").">Socia</option>";
                            echo "</select>";
                            echo "</div>";
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
    Vista::sub_foot("",[
        "Confirmar" => [
            "id" => "Confirmar",
            "title" => "Confirmar la operación"
        ]
    ]);
?>   
<?php endif ?>