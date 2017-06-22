<?php

	require_once '../../libs/Generar.php';

	$g = new Generar();
	$g->set_opciones([
		["Agregar","Agregue a una nueva socia al sistema"],
		["Modificar","Modifique los datos de una socia existente"],
		["Consultar","Consulte los datos de una socia"],
		["Eliminar","Elimine a una socia del sistema"]
	]);
	$g->set_campos([
		[
			[2,"socia_nombre","Nombre",[
				"required"=>true
			]]
			[2,"socia_nombre","Nombre",[
				"required"=>true
			]]
			[2,"socia_nombre","Nombre",[
				"required"=>true
			]]
			[2,"socia_nombre","Nombre",[
				"required"=>true
			]]
		],
		[
		]
	]);


