<?php

class Vista {
    
function render($vista) {
    define("URL_LOCAL",URL."vistas/$vista/");
    require "./vistas/$vista/index.php";
}

// para facilitar la generación de html en las subvistas
/**************************************************************************
$titulo = "titulo-en-string";
$opciones = [
	"boton-texto" => [
		"id" => "boton-id",
		"title" => "boton-title",
		...
	],
	...
]
**************************************************************************/
static function sub_head($titulo, $opciones = null) {

// primero se debe obtener el titulo
$tit_arr = explode(":",$titulo);

// si no hay ningun ":" dentro del titulo
if (count($tit_arr) == 2) {
	$titulo = "<span class='vista_nombre'>".$tit_arr[0]."</span> : <span class='vista_estado'>".$tit_arr[1]."</span>";
}

// se obtienen los botones con las opciones
if ($opciones != null)
{
$opc_arr = [];
foreach ($opciones as $opcion => $arreglo) {
	$prop = [];
	foreach ($arreglo as $tag => $valor) {
		$prop[] = "$tag='$valor'";
	}
	$prop = implode(" ",$prop);
	$opc_arr[] = "<button class='vista_boton pull-right' $prop>$opcion</button>";
}
$opciones = implode("\n",$opc_arr);

echo <<<EOD
<div id='vista_header' class='container-fluid'>
    <div class='row'>
        <div class='col-md-7 col-sm-4 col-xs-12'>
            <p>$titulo</p>
        </div>
        <div id='vista_opciones' class='col-md-5 col-sm-8 col-xs-12'>
            $opciones
        </div>
    </div> 
</div>
EOD;
} else {
echo <<<EOD
<div id='vista_header' class='container-fluid'>
    <div class='row'>
        <div class='col-md-7 col-sm-4 col-xs-12'>
            <p>$titulo</p>
        </div>
    </div> 
</div>
EOD;
}

}  

/**************************************************************************
$mensaje = "mensaje-en-html";
$opciones = [
	"boton-texto" => [
		"id" => "boton-id",
		"title" => "boton-title",
		...
	],
	...
]
**************************************************************************/
static function sub_foot($mensaje, $opciones = null) {

// se obtienen los botones con las opciones
$opc_arr = [];
foreach ($opciones as $opcion => $arreglo) {
	$prop = [];
	foreach ($arreglo as $tag => $valor) {
		$prop[] = "$tag='$valor'";
	}
	$prop = implode(" ",$prop);
	$opc_arr[] = "<button class='vista_boton pull-right' style='margin-top:1px;' $prop>$opcion</button>";
}
$opciones = implode("\n",$opc_arr);

echo <<<EOD
<div id='vista_footer' class='container-fluid'>
    <div class='row'>
        <div class='col-md-10 col-sm-4 col-xs-12'>
            <p class='vista_mensaje'>$mensaje</p>
        </div>
        <div id='vista_footer_opciones' class='col-md-2 col-sm-8 col-xs-12'>
            $opciones
        </div>
    </div> 
</div>
EOD;
}

/**************************************************************************
$tabla = [[cabecera1,...],[contenido1,...],...]
**************************************************************************/
static function sub_table($tabla, $prop = null) {
	
	echo "<div id='tabla-content' hidden>";
	echo "<table id='tabla' class='cell-border hover' width='100%' cellspacing='0'>";
    
    if(count($tabla) > 0)
    {
        echo "<thead>";
	        echo "<tr>";
	        foreach ($tabla[0] as $head) {
	            echo "<th>";
	            echo $head;
	            echo "</th>";
	        }
	        echo "</tr>";
        echo "</thead>";
        $footer = true;
        if (is_array($prop)) {
        	if (array_key_exists("footer", $prop)) {
        		$footer = $prop["footer"];
        	}
        }
        if ($footer == true) {
        	echo "<tfoot>";
		        echo "<tr>";
		        foreach ($tabla[0] as $head) {
		            echo "<th>";
		            echo $head;
		            echo "</th>";
		        }
		        echo "</tr>";
	        echo "</tfoot>";
        }   
        echo "<tbody>";
        foreach ($tabla as $row) {
            if ($row != $tabla[0]) {
                echo "<tr>";
                foreach ($row as $index => $valor) {
                    echo "<td>";
                    $formato = null;
                    if (is_array($prop)) {
			        	if (array_key_exists("format", $prop)) {
			        		$formato = $prop["format"];
			        	}
			        }
			        if ($formato != null) {
			        	if (is_array($formato)) {
			        		if (isset($formato[$index])) {
			        			$funcion = $formato[$index];
			        			if (get_class($funcion) == "Closure") {
			        				echo $funcion($valor);
			        			} else {
			        				echo $valor;
			        			}
			        		} else {
			        			echo $valor;
			        		}
			        	} else {
			        		echo $valor;
			        	}
			        } else {
			        	echo $valor;
			        }
                    echo "</td>";
                }
                echo "</tr>";
            }
        }
        echo "</tbody>";
    }

    echo "</table></div>";
}

/**************************************************************************
$variables = [
	"nombre" => "valor",
	....
]
**************************************************************************/
static function sub_vars($variables) {
	$resultado = [];
	foreach ($variables as $variable => $valor) {
		if (is_array($valor)) {
			$resultado[] = "var $variable = JSON.parse('".json_encode($valor)."');";
		} else {
			$resultado[] = "var $variable = '$valor';";
		}
	}
	$resultado = implode("\n",$resultado);
	echo $resultado;
}

}
