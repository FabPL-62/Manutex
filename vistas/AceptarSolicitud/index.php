<?php if (isset($_POST["llave"])): ?>
<?php $tabla = json_decode($_POST["tabla"]); ?>
<?php Vista::sub_head("Solicitudes de Máquinas:Modificar estados para las solicitudes") ?>
<div id="vista_contenido">
	<?php if(count($tabla) > 0){ ?>
	<table id="tabla" class="cell-border hover" width="100%" cellspacing="0">
        <thead>
            <tr>
            	<th width="50px">Expandir</th>
            	<th><?php echo $tabla[0][0]; ?></th>
            	<th><?php echo $tabla[0][1]; ?></th>
            	<th>Operación</th>
            </tr>
        </thead>
        <tbody>
        <?php
	        foreach ($tabla as $i => $row) {
	        	if ($i > 0) {
	        		echo "<tr class='sol-grupo-fila' style='background:#eee;' fila='$i'>";
	        		echo "<td><span class='glyphicon glyphicon-plus-sign sol-grupo-exp' style='cursor:pointer;'></span></td>";
	        		$cantidades = [0,0,0,0,0];
		            foreach ($row as $j => $valor) {
		            	if ($j < 2) {
		            		echo "<td>";
			                echo $valor;
			                echo "</td>";
		            	} else {
		            		foreach($valor as $elemento) {
		            			$estado = intval($elemento[4]);
		            			$cantidades[$estado] += 1;
		            		}
		            	}
		            }
		            $fecha = strtotime(implode("-",array_reverse(explode("/",$row[0]))));
		            $actual = strtotime(date("Y-m-d"));
		            echo "<td width='241px'>";
					echo "<div class='form-group' style='margin-bottom:0px;'>";
                    echo "<select class='form-control sol-estado' ";
                    if ($cantidades[0] == 0 
                    && $cantidades[1] == 0 
                    && ($cantidades[2] > 0 
                    || $cantidades[3] > 0)
                    && $cantidades[4] == 0) {
                    	echo "disabled";
                    }
                    echo ">";
                    echo "<option value='' selected>Sin acciones</option>";
                    echo "<option value='-1' disabled hidden>Definida por el Usuario</option>";
                    if ($cantidades[0] > 0 || $cantidades[4] > 0) {
                    	echo "<option value='1'>Aceptar</option>";
                    }
                    if ($cantidades[1] > 0) {
                    	if ($fecha <= $actual) {
                    		echo "<option value='2'>Invalidar</option>";
                    	}
                    }
                    if ($cantidades[1] > 0) {
                    	if ($fecha <= $actual) {
							echo "<option value='3'>Validar</option>";
						}
                    }
                    if ($cantidades[0] > 0 || $cantidades[1] > 0) {
						echo "<option value='4'>Rechazar</option>";
                    }
                    	
                    echo "</select>";
                    echo "</div>";
		            echo "</td>";
		            echo "</tr>";
	        	}
	        }
        ?>
        </tbody>
	</table>
	<?php } else { ?>
	No hay solicitudes pendientes.
    <?php } ?>
</div>
<?php Vista::sub_foot("Puede cambiar los estados en la columna de Operación, por fecha o individualmente",[
	"Confirmar" => [
		"id" => "Confirmar",
		"title" => "Confirmar la operación"
	]
]); ?>
<script>
	var mi_tabla = JSON.parse('<?php echo $_POST["tabla"]; ?>');
</script>
<?php endif ?>