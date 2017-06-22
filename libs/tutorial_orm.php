<?php

/****************************************************************************************************************************
	Se inicializa la clase ORM
	- En el sistema que hemos estado realizando, esta clase se inicializa en index.php
	- entiendase [] como parámetro opcional
****************************************************************************************************************************/
require "ORM.php";
$conexion = new Conexion("localhost", "root", "", "bdmanutex");

/****************************************************************************************************************************
	como crear un objeto tabla
****************************************************************************************************************************/
$socia = orm::socia([

	// asignar a un campo algun valor
	orm::socia_rut => 11111111,
	orm::socia_nombre => "Test",
	...

]);

/****************************************************************************************************************************
	insertar un nuevo registro a traves del objeto tabla
	orm::insert(tabla, [conexion])
	- retorna : 
		(1) si fue exitoso
		(0) si hubo algun error
		(null) si ya existe un registro con esa clave primaria
		(string) en formato sql, si no se especifica una conexion

	ejemplo:

		orm::insert(orm::socia([
			orm::socia_rut => 11111111,
			orm::socia_nombre => "Test"
		]),$conexion)

	genera:

		insert into socia (rut_socia,nom_socia) values ('11111111','Test')

****************************************************************************************************************************/
$resultado = orm::insert($socia,$conexion);

/****************************************************************************************************************************
	modificar un registro a traves de un objeto tabla
	orm::update(tabla, [conexion])
	- retorna :
		(1) si fue exitoso
		(0) si hubo algun error
		(string) en formato sql, si no se especifica una conexion

	- cabe destacar que si se define un campo clave en el objeto tabla, este se considerara dentro de la condicion
	  where, de lo contrario, se considera dentro del set

	  ejemplo:

	  	orm::update(orm::socia(
			orm::socia_rut => 11111111,
			orm::socia_estado => 1	       
	  	),$conexion)

	  genera:

	  	update socia set
	  		estado_socia = '1'
	  	where rut_socia = '11111111'

****************************************************************************************************************************/
$resultado = orm::update($socia,$conexion);

/****************************************************************************************************************************
	borrar un registro a través de un objeto tabla
	orm::delete(tabla, [conexion])
	- retorna :
		(1) si fue exitoso
		(0) si hubo algun error
		(array) si hay algun error por dependencia
		(string) en formato sql, si no se define una conexion

	- el arreglo que retorna es de constantes de tabla, es decir, un arreglo de numeros
	  sirve para dar mensajes personalizados en el controlador
	  switch ($arreglo) {
		case orm::socia : $mensaje = "hay socias asociadas al registro que desea eliminar"; break;
		...
	  }

	ejemplo:

		orm::delete(orm::socia([
			orm::socia_rut => 11111111,
			orm::socia_estado => 3
		]),$conexion)

	genera:

		delete from socia
		where rut_socia = '11111111'
		  and estado_socia = '3'

****************************************************************************************************************************/
$resultado = orm::delete($socia, $conexion);

/****************************************************************************************************************************
	buscar registros con un solo objeto tabla (find)
	orm::find(tabla, [seleccion], [conexion])
	- retorna :
		(null) si algun parametro no es correcto
		(string) en formato sql, si no se especifica una conexion, o bien un string en formato json
		(arreglo) personalizado para la respuesta a la peticion

	- por defecto si se define alguna conexion, las siguientes propiedades de la salida se ejecutan
		- formato: true -> se aplica el formato por defecto definido en la clase orm
		- cabeceras: true -> se muestran las cabeceras en el primer registro
		- valores: true -> se muestran solo valores, si es falso, las cabeceras se muestran por cada registro
		- grupo: null -> sirve para aplicar una agrupacion adicional en la salida, por defecto no se agrupa
		- json: false -> se retorna como arreglo, pero si es true, se convierte a json
		- objeto: false -> cada registro en la salida es un objeto tabla
****************************************************************************************************************************/

//---------------------------------------------------------------------------------------------------------------------------
//  generar un string sql, seleccionando sólo los campos definidos en el 2do parámetro
//  select s.nom_socia 'Nombre', s.apP_socia 'Ap. Paterno', s.apM_socia 'Materno' from socia s
//  where s.rut_socia = '11111111'
//---------------------------------------------------------------------------------------------------------------------------
$resultado = orm::find(orm::socia([
	orm::socia_rut => 11111111
]),[
	orm::socia_nombre,
	orm::socia_paterno,
	orm::socia_materno
]);

//---------------------------------------------------------------------------------------------------------------------------
//  generar un string sql, seleccionando todos los campos de la tabla socia, donde rut_socia = '11111111'
//---------------------------------------------------------------------------------------------------------------------------
$resultado = orm::find(orm::socia([
	orm::socia_rut => 11111111
]));

//---------------------------------------------------------------------------------------------------------------------------
//  generar un string sql, seleccionando sólo los campos definidos en el 2do parámetro, ordenando alguno
//  select s.nom_socia 'Nombre', s.apP_socia 'Ap. Paterno', s.apM_socia 'Materno' from socia s
//  where s.rut_socia = '11111111'
//  order by s.nom_socia desc
//---------------------------------------------------------------------------------------------------------------------------
$resultado = orm::find(orm::socia([
	orm::socia_rut => 11111111
]),[
	orm::socia_nombre => "desc",
	orm::socia_paterno,
	orm::socia_materno
]);

//---------------------------------------------------------------------------------------------------------------------------
//  generar un string sql, seleccionando sólo los campos definidos en el 2do parámetro
//  select s.nom_socia 'Nombre', s.apP_socia 'Ap. Paterno', s.apM_socia 'Materno' from socia s
//  where s.rut_socia = '11111111'
//---------------------------------------------------------------------------------------------------------------------------
$resultado = orm::find(orm::socia([
	orm::socia_rut => 11111111
]),[
	orm::socia_nombre,
	orm::socia_paterno,
	orm::socia_materno
]);

//---------------------------------------------------------------------------------------------------------------------------
//  se genera un arreglo de salida, con los registros en el formato por defecto
// [["Nombre","Ap.Paterno","Ap.Materno"],["test","test","test"]]
//---------------------------------------------------------------------------------------------------------------------------
$resultado = orm::find(orm::socia([
	orm::socia_rut => 11111111
]),[
	orm::socia_nombre,
	orm::socia_paterno,
	orm::socia_materno
],$conexion);

//---------------------------------------------------------------------------------------------------------------------------
//  se puede sacar el 2do parametro para seleccionar todos los campos
// [["Rut","Nombre","Ap.Paterno","Ap.Materno",...],["11111111-1","test","test","test",...]]
//---------------------------------------------------------------------------------------------------------------------------
$resultado = orm::find(orm::socia([
	orm::socia_rut => 11111111
]),$conexion);

//---------------------------------------------------------------------------------------------------------------------------
//  algunas propiedades se pueden cambiar, por ejemplo, no mostrar las cabeceras
// [["test","test","test"]]
//---------------------------------------------------------------------------------------------------------------------------
$resultado = orm::find(orm::socia([
	orm::socia_rut => 11111111
]),[
	orm::socia_nombre,
	orm::socia_paterno,
	orm::socia_materno
],[
	"conexion" => $conexion,
	"cabeceras" => false
]);

//---------------------------------------------------------------------------------------------------------------------------
//  las cabeceras se muestran dentro de cada registro
// [["Nombre" => "test","Ap.Paterno" => "test","Ap.Materno" => "test"]]
//---------------------------------------------------------------------------------------------------------------------------
$resultado = orm::find(orm::socia([
	orm::socia_rut => 11111111
]),[
	orm::socia_nombre,
	orm::socia_paterno,
	orm::socia_materno
],[
	"conexion" => $conexion,
	"valores" => false
]);

//---------------------------------------------------------------------------------------------------------------------------
//  las salida puede estar en formato json (string)
// "[{"Nombre":"test","Ap.Paterno":"test","Ap.Materno":"test"}]"
//---------------------------------------------------------------------------------------------------------------------------
$resultado = orm::find(orm::socia([
	orm::socia_rut => 11111111
]),[
	orm::socia_nombre,
	orm::socia_paterno,
	orm::socia_materno
],[
	"conexion" => $conexion,
	"valores" => false,
	"json" => true
]);

//---------------------------------------------------------------------------------------------------------------------------
//  si solo se coloca la constante de tabla, no hay codicion where
// [["Rut","Estado"],["11111111-1","Eliminada"],...]
//---------------------------------------------------------------------------------------------------------------------------
$resultado = orm::find(orm::socia,[
	orm::socia_rut,
	orm::socia_estado
],$conexion);

// lo anterior es equivalente, pero se manda el objeto tabla
// el anterior crea un objeto tabla a traves de la constante de tabla
$resultado = orm::find(orm::socia(),[
	orm::socia_rut,
	orm::socia_estado
],$conexion);

//---------------------------------------------------------------------------------------------------------------------------
//  la salida se puede agrupar si hay registros que tienen valores comunes
// ejemplo : [["Estado","Rut","Nombre"],["1","18563134-6","Fabian Ignacio"],["1",...],["3","11111111-1","test"],["3",...]]
// resulta: [["Estado",["Rut","Nombre"]],["1",[["18563134-6","Fabian Ignacio"],[...]]],["3",[["11111111-1","test"],...]]]
//---------------------------------------------------------------------------------------------------------------------------
$resultado = orm::find(orm::socia,[
	orm::socia_estado,
	orm::socia_rut,
	orm::socia_nombre
],[
	"conexion" => $conexion,
	"grupo" => [
		orm::socia_estado
	]
]);

//---------------------------------------------------------------------------------------------------------------------------
//  se pueden obtener objetos tabla
// [{objeto tabla : campos => [orm::socia_nombre => "test", orm::socia_paterno => "test", orm::socia_materno => "test"]}]
//---------------------------------------------------------------------------------------------------------------------------
$resultado = orm::find(orm::socia([
	orm::socia_rut => 11111111
]),[
	orm::socia_nombre,
	orm::socia_paterno,
	orm::socia_materno
],[
	"conexion" => $conexion,
	"objeto" => true
]);

/****************************************************************************************************************************
	sentencia select
	orm::select(entrada)
	- la entrada debe ser un arreglo, con ciertas keys que lo definen
	orm::select([
		"tablas" => [...],
		"campos" => [...],
		"where" => "...",
		"grupo" => [...],
		"having" => "...",
		"orden" => [...],
		"prop" => [...],
		"salida" => [...]
	])

	- retorna :
		(null) si algun parametro dentro de la entrada no es correcto
		(string) en formato sql, si no se especifica una conexion, o bien un string en formato json
		(arreglo) personalizado para la respuesta a la peticion

	- La clave llamada prop, contiene un arreglo con propiedades para desactivar algunas
	  cosas que se ejecutan por defecto, las cuales son:
	  	- join: las condiciones de join se agregan automaticamente a la sentencia sql
	  	- condicionar: se agregan automaticamente las condiciones que generan los objetos tabla de entrada
	  	- agrupar: se realiza automaticamente la seleccion de los campos que agrupan para la sentencia sql

	- por defecto si se define alguna conexion, las siguientes propiedades de la salida se ejecutan
		- formato: true -> se aplica el formato por defecto definido en la clase orm
		- cabeceras: true -> se muestran las cabeceras en el primer registro
		- valores: true -> se muestran solo valores, si es falso, las cabeceras se muestran por cada registro
		- grupo: null -> sirve para aplicar una agrupacion adicional en la salida, por defecto no se agrupa
		- json: false -> se retorna como arreglo, pero si es true, se convierte a json

****************************************************************************************************************************/

//---------------------------------------------------------------------------------------------------------------------------
//  obtener solo sentencias sql (string)
//---------------------------------------------------------------------------------------------------------------------------

// seleccionar todos los campos de una sola tabla
// select <todos los campos con sus cabeceras> from socia s
$resultado = orm::select([
	"tablas" => orm::socia
]);

// seleccionar todos los campos de una sola tabla, agregando la condicion generada por el objeto tabla
// select <todos los campos con sus cabeceras> from socia s where s.rut_socia = '11111111'
$resultado = orm::select([
	"tablas" => orm::socia([
		orm::socia_rut => 11111111
	])
]);

// seleccionar varias tablas, con todos sus campos, la condicion join se genera automaticamente
// si tienen alguna relacion por dependencia
// select <todos los campos de socia y sus cabeceras>, <todos los campos de asistencia y sus cabeceras>
// from socia s, asistencia a
// where s.rut_socia = a.rut_socia
$resultado = orm::select([
	"tablas" => [orm::socia, orm::asistencia]
]);

// seleccionar varias tablas, con todos sus campos, la condicion join se genera automaticamente
// si tienen alguna relacion por dependencia, agregando una condicion en una tabla
// select <todos los campos de socia y sus cabeceras>, <todos los campos de asistencia y sus cabeceras>
// from socia s, asistencia a
// where s.rut_socia = a.rut_socia
//   and a.fecha_evento = '2015-12-10 00:00:00'
$resultado = orm::select([
	"tablas" => [orm::socia, orm::asistencia([
		orm::asistencia_fecha => "2015-12-10 00:00:00"
	])]
]);

// seleccionar varias tablas, con sólo algunos campos seleccionados
// con las cabeceras por defecto por cada campo seleccionado
// orm::socia_rut_def() = ["Rut" => "s.rut_socia"]
// select s.rut_socia "Rut", a.asistio "Asistencia" from socia s, asistencia a
// where s.rut_socia = a.rut_socia
//   and a.fecha_evento = '2015-12-10 00:00:00'
$resultado = orm::select([
	"tablas" => [orm::socia, orm::asistencia([
		orm::asistencia_fecha => "2015-12-10 00:00:00"
	])],
	"campos" => [
		orm::socia_rut_def(),
		orm::asistencia_asistio_def()
	]
]);

// seleccionar varias tablas, seleccionando todos los campos de una tabla, y algunos de otra
// con las cabeceras por defecto por cada campo seleccionado
// orm::socia_all() = ["Rut" => "s.rut_socia", "Nombre" => "s.nom_socia", ...]
// select <campos y cabeceras de socia>, a.asistio "Asistencia" from socia s, asistencia a
// where s.rut_socia = a.rut_socia
//   and a.fecha_evento = '2015-12-10 00:00:00'
$resultado = orm::select([
	"tablas" => [orm::socia, orm::asistencia([
		orm::asistencia_fecha => "2015-12-10 00:00:00"
	])],
	"campos" => [
		orm::socia_all(),
		orm::asistencia_asistio_def()
	]
]);

// seleccionar varias tablas, seleccionando casi todos los campos de una tabla, y algunos de otra
// con las cabeceras por defecto por cada campo seleccionado
// orm::socia_all([orm::socia_contraseña]) = ["Rut" => "s.rut_socia", "Nombre" => "s.nom_socia", ...] sin el par "Contraseña" => "s.contraseña"
// select <campos y cabeceras de socia sin el campo contraseña>, a.asistio "Asistencia" from socia s, asistencia a
// where s.rut_socia = a.rut_socia
//   and a.fecha_evento = '2015-12-10 00:00:00'
$resultado = orm::select([
	"tablas" => [orm::socia, orm::asistencia([
		orm::asistencia_fecha => "2015-12-10 00:00:00"
	])],
	"campos" => [
		orm::socia_all([orm::socia_contraseña]),
		orm::asistencia_asistio_def()
	]
]);

// seleccionar varias tablas, seleccionando casi todos los campos de una tabla, y algunos de otra
// con las cabeceras por defecto por cada campo seleccionado
// orm::socia_all([orm::socia_contraseña]) = ["Rut" => "s.rut_socia", "Nombre" => "s.nom_socia", ...] sin el par "Contraseña" => "s.contraseña"
// select <campos y cabeceras de socia sin el campo contraseña>, a.asistio "Asistencia" from socia s, asistencia a
// where s.rut_socia = a.rut_socia
//   and a.fecha_evento = '2015-12-10 00:00:00'
$resultado = orm::select([
	"tablas" => [orm::socia, orm::asistencia([
		orm::asistencia_fecha => "2015-12-10 00:00:00"
	])],
	"campos" => [
		orm::socia_all([orm::socia_contraseña]),
		orm::asistencia_asistio_def()
	]
]);