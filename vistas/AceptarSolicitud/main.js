// arreglo para actualizar la informacion en la base de datos
/************************************************************
formato : {
    "0000-00-00" : [["rut","codigo","numero","turno","estado"],...],
    ...
}
************************************************************/
var resultado_general = {};

// para reiniciar el resultado general
function reiniciar_resultado() {
    for (var i in mi_tabla) {
        if (i > 0) {
            var mi_fecha = mi_tabla[i][0];
            resultado_general[mi_fecha] = [];
        }
    }
}

// obtener alto para la tabla
function alto_tabla() {
    return $("#vista_contenido").height()-50;
}

// html para crear la subtabla
function formato_tabla(indice)
{
    // obtenemos la fecha
    var fecha = mi_tabla[indice][0];

    // obtenemos las cabeceras
    var heads = mi_tabla[0][2];

    // obtenemos el cuerpo
    var body = $.extend(true, [], mi_tabla[indice][2]);

    var retorna = "<table id='tabla-"+indice+"' class='cell-border hover' width='100%' cellspacing='0'>"
                 +"<thead><tr style='background:#bbb;'>";

    // agregamos las cabeceras
    for (var i in heads) {
        var elemento = heads[i];
        retorna += "<th>"+elemento+"</th>";
    }
    retorna += "<th>Operación</th>";
    retorna += "</tr></thead><tbody>";

    // agregamos el cuerpo
    for (var i in body) {

        // obtenemos la fila completa
        var fila = body[i];

        // iniciamos la fila
        retorna += "<tr class='sol-fila' grupo='"+indice+"' fila='"+i+"'>";

        // variables que identifican a una fila
        var rut = "";
        var estado = "";
        var codigo = "";
        var numero = "";
        var turno = "";

        // recorremos los elementos de la fila
        for (var j in fila) {

            // obtenemos el rut
            if (j == 0) rut = fila[j].split("-")[0];

            // obtenemos el codigo y numero de maquina
            if (j == 2) codigo = fila[j];
            if (j == 3) numero = fila[j];

            // obtenemos el estado registrado en la base de datos
            if (j == 4) {
                estado = fila[j];

                // formateamos el estado
                if (fila[j] == "0") fila[j] = "Pendiente";
                else if (fila[j] == "1") fila[j] = "Aceptada";
                else if (fila[j] == "2") fila[j] = "Invalidada";
                else if (fila[j] == "3") fila[j] = "Validada";
                else if (fila[j] == "4") fila[j] = "Rechazada";
            }

            // obtenemos el turno
            if (j == 5) turno = fila[j];

            // agregamos el elemento a la tabla
            retorna += "<td>"+fila[j]+"</td>";
        }

        // obtenemos la opcion general, para cargarla a la tabla si es necesario
        var grupo_opcion = "";
        for (var j in resultado_general[fecha]) {
            var mi_elemento = resultado_general[fecha][j];
            if (mi_elemento[0] == rut
            && mi_elemento[1] == codigo
            && mi_elemento[2] == numero
            && mi_elemento[3] == turno){
                grupo_opcion = mi_elemento[4];
            }
        }

        // convertimos fecha y obtenemos la actual
        var mi_fecha = moment(fecha,"DD/MM/YYYY").toDate();
        var actual = moment(new Date()).toDate();

        // agregamos el selector por fila dependiendo del estado
        if (estado == "0") {
            retorna += "<td width='230px'><div class='form-group' style='margin-bottom:0px'>";
            retorna += "<select class='form-control sol-ind'>";
            retorna += "<option value='' ";
            if (grupo_opcion == "") retorna += "selected";
            retorna += ">Sin acciones</option>";
            retorna += "<option value='1' ";
            if (grupo_opcion == "1") retorna += "selected";
            retorna += ">Aceptar</option>";
            retorna += "<option value='4' ";
            if (grupo_opcion == "4") retorna += "selected";
            retorna += ">Rechazar</option>";
        }
        else if (estado == "1") {
            retorna += "<td width='230px'><div class='form-group' style='margin-bottom:0px'>";
            retorna += "<select class='form-control sol-ind'>";
            retorna += "<option value='' ";
            if (grupo_opcion == "") retorna += "selected";
            retorna += ">Sin acciones</option>";
            if (mi_fecha <= actual) {
                retorna += "<option value='3' ";
                if (grupo_opcion == "3") retorna += "selected";
                retorna += ">Validar</option>";
                retorna += "<option value='2' ";
                if (grupo_opcion == "2") retorna += "selected";
                retorna += ">Invalidar</option>";
            }
            retorna += "<option value='4' ";
            if (grupo_opcion == "4") retorna += "selected";
            retorna += ">Rechazar</option>";
        }
        else if (estado == "2") {
            retorna += "<td width='230px'><div class='form-group' style='margin-bottom:0px'>";
            retorna += "<select class='form-control sol-ind' disabled>";
            retorna += "<option value='' disabled selected hidden>No hay acciones</option>"
        }
        else if (estado == "3") {
            retorna += "<td width='230px'><div class='form-group' style='margin-bottom:0px'>";
            retorna += "<select class='form-control sol-ind' disabled>";
            retorna += "<option value='' disabled selected hidden>No hay acciones</option>"
        }
        else if (estado == "4") {
            retorna += "<td width='230px'><div class='form-group' style='margin-bottom:0px'>";
            retorna += "<select class='form-control sol-ind'>";
            retorna += "<option value='' ";
            if (grupo_opcion == "") retorna += "selected";
            retorna += ">Sin acciones</option>";
            retorna += "<option value='1' ";
            if (grupo_opcion == "1") retorna += "selected";
            retorna += ">Aceptar</option>";
        }
        retorna += "</select>";
        retorna += "</div>";
        retorna += "</td>";
        retorna += "</tr>";
    }

    retorna += "</tbody></table>";
    return retorna;
}

// agrega el evento a un selector de estado en una fila
function actualizar_eventos() {

    // se agrega el evento a la clase .sol-ind (combobox)
    $(".sol-ind").on("change",function(){
        
        // obtenemos el grupo y la fila
        var grupo = parseInt($(this).parents(".sol-fila").attr("grupo"));
        var fila = parseInt($(this).parents(".sol-fila").attr("fila"));

        // obtenemos la opcion seleccionada
        var opcion = $(this).val();
        var selector = ".sol-grupo-fila[fila="+grupo+"] .sol-estado";

        // cambiamos el estado de la accion de grupo (Definido por el usuario)
        $(selector).val("-1");

        // obtenemos los datos de la fila
        var datos = $.extend(true, [], mi_tabla[grupo][2][fila]);
        var rut = datos[0].split("-")[0];

        // obtenemos la fecha del grupo
        var fecha = mi_tabla[grupo][0];

        // actualizamos el resultado
        var existe = -1;
        for (var i in resultado_general[fecha]) {
            var mi_elemento = resultado_general[fecha][i];
            if (mi_elemento[0] == rut
            && mi_elemento[1] == datos[2]
            && mi_elemento[2] == datos[3]
            && mi_elemento[3] == datos[5]){
                existe = i;
            }
        }
        if (opcion != "") {
            if (existe == -1) {
                resultado_general[fecha].push([
                    rut,
                    datos[2],
                    datos[3],
                    datos[5],
                    opcion
                ]);
            } else {
                resultado_general[fecha][existe][4] = opcion;
            }
        }
        else {
            if (existe != -1) {
                resultado_general[fecha].splice(existe,1);
                if (resultado_general[fecha].length == 0) {
                    $(selector).val("");
                }
            }
        } 
    });
}

var tabla;
var tabla_existe = [];
$(document).ready(function() { 
   
    // se carga la forma como datatable
    tabla = $('#tabla').DataTable({
        "scrollY": alto_tabla(),
        "scrollX": true,
        "paging": false,
        "searching": false,
        "ordering": false,
        "info": false
    });

    // se resetea el resultado general
    reiniciar_resultado();

    // agrega un evento que oye cuando se hace un click sobre el +
    $('#tabla tbody').on('click', 'td span.sol-grupo-exp', function () {

        // se obtiene el grupo de fila que lo contiene
        var tr = $(this).parents(".sol-grupo-fila");

        // se obtiene el numero de fila
        var indice = parseInt(tr.attr("fila"));

        // se obtiene la fila de la tabla
        var row = tabla.row(tr);

        // si la tabla se muestra
        if (row.child.isShown()) 
        {
            // se cierra la fila
            row.child.hide();
            tr.removeClass('shown');

            // se cambia el icono a +
            $(this).removeClass("glyphicon-minus-sign");
            $(this).addClass("glyphicon-plus-sign");
        }
        else 
        {
            // si la tabla no existe
            if ($.inArray(indice, tabla_existe) == -1) {

                // se agrega el contenido a la fila
                row.child(formato_tabla(indice)).show();

                // se agrega el evento al selector de estado por fila
                actualizar_eventos();

                // la tabla ya existe
                tabla_existe.push(indice);
            }
            // la tabla ya existe, solo se muestra
            else {
                row.child.show();
            }

            // se actualizan los combobox de la subtabla
            actualizar_subtabla(indice);

            // se da un background a la subtabla
            row.child().css({"background":"#ddd"});
            tr.addClass('shown');

            // se muestra el icono -
            $(this).removeClass("glyphicon-plus-sign");
            $(this).addClass("glyphicon-minus-sign");
        }
    });
});

// para actualizar los combobox en la subtabla
function actualizar_subtabla(grupo) {

    // se obtiene el objeto de la fila del grupo
    var grupo_objeto = $(".sol-grupo-fila[fila="+grupo+"]");

    // se obtiene la operacion general del grupo
    var operacion = grupo_objeto.find(".sol-estado").val();

    // se obtiene la fecha del grupo
    var fecha = mi_tabla[grupo][0];

    // se obtiene el objeto de la fila
    var fila_objeto = $(".sol-fila[grupo="+grupo+"]");

    // si existen filas asociadas al grupo
    if (fila_objeto.length > 0)
    {
        // se recorre la tabla y se actualiza en la vista
        fila_objeto.each(function(index, el) {

            // se obtienen los datos de la tabla
            var rut = mi_tabla[grupo][2][index][0].split("-")[0];
            var codigo = mi_tabla[grupo][2][index][2];
            var numero = mi_tabla[grupo][2][index][3];
            var estado = mi_tabla[grupo][2][index][4];
            var turno = mi_tabla[grupo][2][index][5];

            // se obtiene el objeto del selector general
            var combo = $(this).find(".sol-ind");

            // se ven si hay cambios, y se asigna si hay
            var cambio = false;
            if (operacion == "1") {
                if (estado == "0" || estado == "4") {
                    combo.val(operacion);
                    cambio = true;
                }
            }
            else if (operacion == "2") {
                if (estado == "1") {
                    combo.val(operacion);
                    cambio = true;
                }
            }
            else if (operacion == "3") {
                if (estado == "1") {
                    combo.val(operacion);
                    cambio = true;
                }
            }
            else if (operacion == "4") {
                if (estado == "0" || estado == "1") {
                    combo.val(operacion);
                    cambio = true;
                }
            }
            else if (operacion == "") {
                if (estado == "0" || estado == "1" || estado == "4") {
                    combo.val(operacion);
                    cambio = true;
                }
            }

            // si hubo cambios
            if (cambio == true) {

                // actualizamos el resultado
                var existe = -1;
                for (var i in resultado_general[fecha]) {
                    var mi_elemento = resultado_general[fecha][i];
                    if (mi_elemento[0] == rut
                    && mi_elemento[1] == codigo
                    && mi_elemento[2] == numero
                    && mi_elemento[3] == turno){
                        existe = i;
                    }
                }
                if (operacion != "") {
                    if (existe == -1) {
                        resultado_general[fecha].push([
                            rut,
                            codigo,
                            numero,
                            turno,
                            operacion
                        ]);
                    } else {
                        resultado_general[fecha][existe][4] = operacion;
                    }
                }
                else {
                    if (existe != -1) {
                        resultado_general[fecha].splice(existe,1);
                    }
                } 
            }
        });
    }
    else
    {
        // se recorre la tabla definida para llenar el resultado
        for (var i in mi_tabla[grupo][2])
        {
            var mi_fila = mi_tabla[grupo][2][i];
            var rut = mi_fila[0].split("-")[0];
            var codigo = mi_fila[2];
            var numero = mi_fila[3];
            var estado = mi_fila[4];
            var turno = mi_fila[5];
            var cambio = false;
            if (operacion == "1") {
                if (estado == "0") {
                    cambio = true;
                }
            }
            else if (operacion == "2") {
                if (estado == "1") {
                    cambio = true;
                }
            }
            else if (operacion == "3") {
                if (estado == "1") {
                    cambio = true;
                }
            }
            else if (operacion == "4") {
                if (estado == "0" || estado == 1) {
                    cambio = true;
                }
            }
            else if (operacion == "") {
                if (estado == "0" || estado == "1" || estado == "4") {
                    cambio = true;
                }
            }
            if (cambio == true) {
                // actualizamos el resultado
                var existe = -1;
                for (var j in resultado_general[fecha]) {
                    var mi_elemento = resultado_general[fecha][j];
                    if (mi_elemento[0] == rut
                    && mi_elemento[1] == codigo
                    && mi_elemento[2] == numero
                    && mi_elemento[3] == turno){
                        existe = j;
                    }
                }
                if (operacion != "") {
                    if (existe == -1) {
                        resultado_general[fecha].push([
                            rut,
                            codigo,
                            numero,
                            turno,
                            operacion
                        ]);
                    } else {
                        resultado_general[fecha][existe][4] = operacion;
                    }
                }
                else {
                    if (existe != -1) {
                        resultado_general[fecha].splice(existe,1);
                    }
                } 
            }
        }
    }
}

// actualizar selector general de estado por gruo de fila
function actualizar_selector(grupo)
{
    // se limpia todo el selector
    var cantidades = [0,0,0,0,0];
    for (var i in mi_tabla[grupo][2]) {
        var estado = parseInt(mi_tabla[grupo][2][i][4]);
        cantidades[estado] += 1;
    }

    // se obtiene el objeto
    var selector = $(".sol-grupo-fila[fila="+grupo+"] .sol-estado");

    // limpiamos el selector
    selector.empty();
    var mi_html = "";

    // obtenemos la fecha
    var fecha = mi_tabla[grupo][0];

    // convertimos fecha y obtenemos la actual
    fecha = moment(fecha,"DD/MM/YYYY").toDate();
    var actual = moment(new Date()).toDate();

    if (cantidades[0] == 0 
    && cantidades[1] == 0 
    && (cantidades[2] > 0 
    || cantidades[3] > 0)
    && cantidades[4] == 0) {
        selector.prop("disabled",true);
    }

    // agregamos los elementos
    mi_html += "<option value='' selected>Sin acciones</option>";
    mi_html += "<option value='-1' disabled hidden>Definida por el Usuario</option>";
    if (cantidades[0] > 0 || cantidades[4] > 0) {
        mi_html += "<option value='1'>Aceptar</option>";
    }
    if (cantidades[1] > 0) {
        if (fecha <= actual) {
            mi_html += "<option value='2'>Invalidar</option>";
        }
    }
    if (cantidades[1] > 0) {
        if (fecha <= actual) {
            mi_html += "<option value='3'>Validar</option>";
        }
    }
    if (cantidades[0] > 0 || cantidades[1] > 0) {
        mi_html += "<option value='4'>Rechazar</option>";
    }

    // agregamos los elementos al selector
    selector.append(mi_html);
}

// si se selecciona una accion en una fila
$(".sol-estado").on("change",function(){
    var grupo = $(this).parents(".sol-grupo-fila").attr("fila");
    actualizar_subtabla(grupo);
});

// confirmar modificaciones
$("#Confirmar").click(function(){
    var cantidad = 0;
    for (var fecha in resultado_general)
    {
        var arreglo = resultado_general[fecha];
        if (arreglo.length > 0) cantidad += 1;
    }
    if (cantidad > 0)
    {
        mensaje_confirmar(
            "Confirmar modificaciones",
            "Esta segura que desea modificar las solicitudes implicadas?",
        function(){
            var json = JSON.stringify(resultado_general);
            $.ajax({
                type:"POST",
                url: url_global+"Maquina/modificar_aceptar",
                data:{
                    json: json,
                    llave: true
                }
            }).done(function(response){
                if (response != 0) {
                    mi_tabla = JSON.parse(response);
                    $("#tabla tbody .sol-grupo-fila").each(function(i,v){
                        var tr = $(this);
                        var indice = parseInt(tr.attr("fila"));
                        var row = tabla.row(tr);

                        // si el child se esta mostrando, se actualiza
                        if (row.child.isShown()) 
                        {
                            // se cierra la fila
                            row.child(formato_tabla(indice)).show();

                            // se agrega el evento a la clase .sol-ind (combobox)
                            actualizar_eventos();
                            reiniciar_resultado();
                            actualizar_subtabla(indice);
                            row.child().css({"background":"#ddd"});
                            tr.addClass('shown');
                        }
                        else {
                            tabla_existe.splice( $.inArray(indice, tabla_existe), 1 );
                        }
                        $("#vista_footer p").text("La modificación ha sido realizada exitosamente");
                        actualizar_selector(indice);
                    });
                } 
                else {
                    mensaje_error("No se pudieron realizar las modificaciones");
                }
            });
        },function(){
            $("#vista_footer p").text("Se canceló la operación de modificación");
        });
    }
    else {
        $("#vista_footer p").text("No se realizó ninguna acción para modificar");
    }
    
});