// 1: agregar
// 2: modificar
// 3.1: consultar por evento
// 3.2: consultar por total anual
// 4: eliminar
var estado = 1;

// para saber cuando se puede seleccionar todo y cuando no
var estado_seleccion = -1;

// año actual
var año_actual = parseInt(moment(new Date()).format("YYYY"));

// obtener alto para la tabla
function alto_tabla() 
{
    if (estado >= 3) return $("#vista_contenido").height()-100;
    else return Math.max($("#vista_contenido").height()-$(".formulario").height()-50,250);
}

var tabla;
var fila_select;
var fila_index = -1;
$(document).ready(function() { 

    $('#tabla tbody').on( 'click', 'tr', function () {
        if (estado_seleccion != -1) {
            $(this).toggleClass('selected');
        }
    });
   
    // se carga la forma como datatable
    tabla = $('#tabla').DataTable({
        "scrollY": alto_tabla(),
        "scrollX": true,
        "paging": false,
        "searching": false,
        "info": false
    });
    tabla.column(4).visible(false);
    tabla.column(5).visible(false);
    tabla.column(6).visible(false);

    // se ejecuta para mostrar el contenido de la tabla
    $(function(){
        $("#tabla-content").show();
        tabla.draw();
    });
});

// cuando se redimensiona la ventana del navegador
$(window).resize(function(){
    $("div.dataTables_scrollBody").height(alto_tabla());
});

$("#seleccionar").click(function(){
    if (estado_seleccion <= 1){
        $("#tabla tbody tr").addClass("selected");
        $(this).text("Deseleccionar todas");
        estado_seleccion = 2;
    }else {
        $("#tabla tbody tr").removeClass("selected"); 
        $(this).text("Seleccionar todas");
        estado_seleccion = 1;
    }
});

// cambio de estados al seleccionar opciones--------------------------------------------------------
// cuando se presiona agregar
$("#MantAgregar").click(function(){
    if (estado != 1) {

        $("#vista_header p").text("Asistencia : Ingresar");
        $("#vista_footer p").html("Seleccione un Evento");
        $("#seleccionar").hide();
        $(".general").show();
        $(".consulta-anual").hide();
        tabla.column(4).visible(false);
        tabla.column(5).visible(false);
        tabla.column(6).visible(false);
        $("#MantConfirmar").hide();
        $("#tabla tbody tr").removeClass("selected"); 
        $("#tabla tbody").show();
        $("#fecha").val("");
        $("#seleccionar").text("Seleccionar todas");
        $("#MantConfirmar").text("Confirmar");
        estado_seleccion = -1;
        estado = 1;

        // se vuelve a cargar la lista de socias que estan en la actualidad
        // se vuelve a cargar la lista de eventos sin asistencias
        $.ajax({
            type: "POST",
            url: url_global+"Socia/lista_consultar",
            data: {
                llave: true
            }
        }).done(function(response1){
            $.ajax({
                type: "POST",
                url: url_global+"Evento/consultar_eventos",
                data: {
                    estado: 1,
                    llave: true
                }
            }).done(function(response2){

                // borramos los eventos
                $("#fecha").children("option").each(function(i,v){
                    if ($(this).attr("value") != "") {
                        $(this).remove();
                    }
                });

                // cargamos los eventos que no tienen asistencias
                var eventos = JSON.parse(response2);
                if (eventos !== null) {
                    for (var i in eventos) {
                        var mi_tipo = eventos[i][0];
                        var mi_fecha = eventos[i][1];
                        $("#fecha").append("<option value='"+mi_fecha+"'>"+mi_tipo+" : "+mi_fecha+"</option>");
                    }
                    $("#fecha").prop("disabled",false);
                } else {
                    $("#vista_footer p").text("No hay eventos sin asistencias");
                    $("#fecha").prop("disabled",true);
                }

                // borramos todos los elementos de la tabla
                tabla.clear().draw();

                // agregamos la lista de socias actuales
                var socias = JSON.parse(response1);
                for (var i in socias) {
                    if (i > 0) {
                        var socia = socias[i];
                        socia.push("");
                        socia.push("");
                        socia.push("");
                        tabla.row.add(socia);
                    }
                }
                tabla.draw();
            });
        });
        $("#fecha").focus();
    }
});
// cuando se presiona modificar
$("#MantModificar").click(function(){
    if (estado != 2) {

        $("#vista_header p").text("Asistencia : Modificar");
        $("#seleccionar").hide();
        $(".general").show();
        $(".consulta-anual").hide();
        tabla.column(4).visible(false);
        tabla.column(5).visible(false);
        tabla.column(6).visible(false);
        $("#MantConfirmar").hide();
        $("#fecha").val("");
        $("#seleccionar").text("Seleccionar todas");
        $("#MantConfirmar").text("Confirmar");
        estado_seleccion = -1;
        estado = 2;

        // se oculta la lista de socias para mantener aspecto
        $("#tabla tbody").hide();

        // se hace peticion ajax para actualizar eventos
        $.ajax({
            type:"POST",
            url:url_global+"Evento/consultar_eventos",
            data: {
                estado: 2,
                llave: true
            }
        }).done(function(response){
            
            // se borran todos los eventos
            $("#fecha").children("option").each(function(i,v){
                if ($(this).attr("value") != "") {
                    $(this).remove();
                }
            });

            // se cargan los eventos con asistencias
            var eventos = JSON.parse(response);
            if (eventos.length > 0) {
                for (var i in eventos) {
                    var mi_tipo = eventos[i][0];
                    var mi_fecha = eventos[i][1];
                    $("#fecha").append("<option value='"+mi_fecha+"'>"+mi_tipo+" : "+mi_fecha+"</option>");
                }
                $("#vista_footer p").html("Seleccione un Evento");
                $("#fecha").prop("disabled",false);
            } else {
                $("#vista_footer p").text("No hay eventos con asistencias");
                $("#fecha").prop("disabled",true);
            }

        });
        $("#fecha").focus();
    }
});

// cuando se presiona consultar por evento
$("#vista_opciones .dropdown .dropdown-menu a[columna=1]").click(function(){
    if (estado != 3.1) {

        $("#vista_header p").text("Asistencia : Consultar por evento");
        $("#seleccionar").hide();
        $(".general").show();
        $(".consulta-anual").hide();
        tabla.column(4).visible(false);
        tabla.column(5).visible(false);
        tabla.column(6).visible(false);
        $("#MantConfirmar").text("Generar Informe");
        $("#fecha").val("");
        estado_seleccion = -1;
        estado = 3.1;

        // se oculta la lista de socias para mantener aspecto
        $("#tabla tbody").hide();

        // se hace peticion ajax para actualizar eventos
        $.ajax({
            type:"POST",
            url:url_global+"Evento/consultar_eventos",
            data: {
                estado: 2,
                llave: true
            }
        }).done(function(response){
            
            // se borran todos los eventos
            $("#fecha").children("option").each(function(i,v){
                if ($(this).attr("value") != "") {
                    $(this).remove();
                }
            });

            // se cargan los eventos con asistencias
            var eventos = JSON.parse(response);
            if (eventos.length > 0) {
                for (var i in eventos) {
                    var mi_tipo = eventos[i][0];
                    var mi_fecha = eventos[i][1];
                    $("#fecha").append("<option value='"+mi_fecha+"'>"+mi_tipo+" : "+mi_fecha+"</option>");
                }
                $("#vista_footer p").html("Seleccione un Evento");
                $("#fecha").prop("disabled",false);
            } else {
                $("#vista_footer p").text("No hay eventos con asistencias");
                $("#fecha").prop("disabled",true);
            }

        });
        $("#fecha").focus();
    }
});

$("#vista_opciones .dropdown .dropdown-menu a[columna=2]").click(function(){
    if (estado != 3.2) {

        $("#vista_header p").text("Asistencia : Consultar total anual por socias");
        $("#seleccionar").hide();
        $(".general").hide();
        $(".consulta-anual").show();
        tabla.column(4).visible(true);
        tabla.column(5).visible(true);
        tabla.column(6).visible(true);
        $(".form-group").removeClass("has-error");
        $("#total-año").css({"background-color":""});
        $("#MantConfirmar").text("Generar Informe");
        $("#total-año").val(año_actual);
        estado_seleccion = -1;
        estado = 3.2;

        $("#vista_footer p").html("Ingrese el año que desea consultar, y confirme para ver los datos");

        // se oculta la lista de socias para mantener aspecto
        $("#tabla tbody").hide();
        $("total-año").focus();
    }
});

// cuando se cambia la seleccion de un evento
var tipo = "T";
$('#fecha').on( 'change', function(){

    // si se requiere agregar
    if (estado == 1) 
    {
        $("#MantConfirmar").show();
        $("#tabla tbody tr").removeClass("selected"); 

        $("#vista_footer p").text("Seleccione presionando sobre una fila,"
            +"deseleccione presionando nuevamente");

        // se puede seleccionar
        $("#seleccionar").show();
        $("#seleccionar").text("Seleccionar todas");
        estado_seleccion = 1;
    }
    // si se requiere modificar
    else if (estado == 2) 
    {
        $("#MantConfirmar").show();

        $("#vista_footer p").text("Seleccione presionando sobre una fila,"
            +"deseleccione presionando nuevamente");

        // se puede seleccionar
        $("#seleccionar").show();
        $("#seleccionar").text("Seleccionar todas");
        estado_seleccion = 1;

        // se obtiene y formatea la fecha para la consulta
        var fecha = $("#fecha").val();
        var fecha_hora = fecha.split(" ");
        var fecha_array = fecha_hora[0].split("/");
        fecha = fecha_array[2]+"-"+fecha_array[1]+"-"+fecha_array[0];
        fecha = fecha + " " + fecha_hora[1]+":00";

        // se hace una peticion para recargar la lista de socias
        $.ajax({
            type: "POST",
            url: url_global+"Evento/consultar_socias",
            data: {
                evento_fecha: fecha,
                llave: true
            }
        }).done(function(response){

            // borramos todos los elementos de la tabla
            tabla.clear();

            // agregamos la lista de socias actuales
            var socias = JSON.parse(response);
            for (var i in socias) {
                var socia = socias[i];
                socia.push("");
                socia.push("");
                socia.push("");
                tabla.row.add(socia);
            }

            // se muestra la tabla
            $("#tabla tbody").show();

            // dibujamos la tabla
            tabla.draw();

            // seleccionamos las socias que asistieron
            $("#tabla tbody tr").each(function(i,v){
                var seleccionado = false;
                var rut = $(this).children("td:first-child").text();
                for (var ii in socias) {
                    if (socias[ii][0] == rut) {
                        if (socias[ii][4] == "1") {
                            seleccionado = true;
                            break;
                        }
                    }
                }
                if (seleccionado == true) {
                    $(this).addClass("selected");
                }
            });
        });
    }
    // si se requiere consultar
    else if (estado == 3.1) 
    {
        $("#MantConfirmar").show();

        // se obtiene y formatea la fecha para la consulta
        var fecha = $("#fecha").val();
        var fecha_hora = fecha.split(" ");
        var fecha_array = fecha_hora[0].split("/");
        fecha = fecha_array[2]+"-"+fecha_array[1]+"-"+fecha_array[0];
        fecha = fecha + " " + fecha_hora[1]+":00";

        // obtenemos el tipo de evento
        tipo = $("#fecha option:selected").text().split(":")[0].trim();
        if (tipo == "O") tipo = "a la Reunión del ";
        else if (tipo == "E") tipo = "a la Reunión del ";
        else tipo = "al Taller del ";

        // se hace una peticion para recargar la lista de socias
        $.ajax({
            type: "POST",
            url: url_global+"Evento/consultar_socias",
            data: {
                evento_fecha: fecha,
                llave: true
            }
        }).done(function(response){

            // borramos todos los elementos de la tabla
            tabla.clear();

            // agregamos la lista de socias actuales
            var socias = JSON.parse(response);
            for (var i in socias) {
                var socia = socias[i];
                socia.push("");
                socia.push("");
                socia.push("");
                tabla.row.add(socia);
            }

            // se muestra la tabla
            $("#tabla tbody").show();

            // dibujamos la tabla
            tabla.draw();

            // seleccionamos las socias que asistieron
            var tot = socias.length;
            var asi = 0;
            // seleccionamos las socias que asistieron
            $("#tabla tbody tr").each(function(i,v){
                var seleccionado = false;
                var rut = $(this).children("td:first-child").text();
                for (var ii in socias) {
                    if (socias[ii][0] == rut) {
                        if (socias[ii][4] == "1") {
                            seleccionado = true;
                            break;
                        }
                    }
                }
                if (seleccionado == true) {
                    $(this).addClass("selected");
                    asi++;
                }
            });
            $("#vista_footer p").text("Asistencia : "+Math.round(asi/tot*100)+"%");
        });
    }
});

$("#MantConfirmar").click(function(){
    
    if (estado == 1 || estado == 2) {
        // se obtiene la lista de socias y se formatea a json
        var arreglo_ruts = [];
        $("#tabla tbody tr").each(function(i,v){
            var mi_rut;
            var asiste = $(this).hasClass("selected");
            $(this).find('td').each(function(ii,vv){
                if (ii==0) {
                    mi_rut = $(this).text().split("-")[0];
                }
            });
            arreglo_ruts.push([mi_rut,asiste]);
        });
        var asistencia = JSON.stringify(arreglo_ruts);

        // se obtiene y formatea la fecha para la consulta
        var fecha = $("#fecha").val();
        var fecha_hora = fecha.split(" ");
        var fecha_array = fecha_hora[0].split("/");
        fecha = fecha_array[2]+"-"+fecha_array[1]+"-"+fecha_array[0];
        fecha = fecha + " " + fecha_hora[1]+":00";
    }

    // si se requiere agregar
    if (estado == 1)
    {
        // se hace la peticion para agregar una asistencia
        $.ajax({
            type: "POST",
            url: url_global+"Evento/agregar_asistencia",
            data: {
                asistencia: asistencia,
                fecha: fecha,
                llave: true
            }
        }).done(function(response){

            var mensaje = JSON.parse(response);
            if (mensaje[0] == 1){
                $("#vista_footer p").text(mensaje[1]);
                // se borra el evento de la lista de eventos
                $("#fecha option:selected").remove();
                ("#vista_header p").text("Asistencia : Ingresar");
                $("#vista_footer p").html("Seleccione un Evento");
                $("#seleccionar").hide();
                $("#MantConfirmar").hide();
                $("#tabla tbody tr").removeClass("selected");
                $("#seleccionar").text("Seleccionar todas");
                $("#MantConfirmar").text("Confirmar");
                estado_seleccion = -1;
                $("#fecha").val("");
                $("#fecha").focus();

                var eventos_disp = $("#fecha option").length;
                if (eventos_disp == 1) {
                    $("#fecha").prop("disabled",true);
                }
            }
            else{
                $("#vista_footer p").text(mensaje[1]);
            }
        });
    }
    // si se requiere modificar
    else if (estado == 2)
    {
        mensaje_confirmar("Confirmar modificación","Esta segura que desea modificar esta asistencia?",
        function(){
            // se hace la peticion para modificar una asistencia por si hubo algun error
            $.ajax({
                type: "POST",
                url: url_global+"Evento/modificar_asistencia",
                data: {
                    asistencia: asistencia,
                    fecha: fecha,
                    llave: true
                }
            }).done(function(response){
                var mensaje = JSON.parse(response);
                $("#vista_footer p").text(mensaje[1]);
            });
        },function(){
            $("#vista_footer p").text("Se canceló la operación de modificación");
        });
    }
    // si se requiere consultar por evento
    else if (estado == 3.1)
    {
        // tabla que se enviara a pdfmake para generar el documento
        var mi_tabla = [];

        // se obtiene el arreglo de elementos de la tabla actual
        var arr_tabla = tabla.rows().data().toArray();

        // se agregan las cabeceras a la tabla
        var mi_cabecera = [
            {text:"Rut",bold:true,fillColor:"#DBDBDB"},
            {text:"Nombre Completo",bold:true,fillColor:"#DBDBDB"},
            {text:"Asistio",bold:true,fillColor:"#DBDBDB"}
        ];
        mi_tabla.push(mi_cabecera);

        // se recorre el arreglo
        for (var i in arr_tabla) 
        {
            // se obtiene la fila
            var fila = arr_tabla[i];
            var mi_fila = [];

            // para unir en una columna todo el nombre
            var mi_nombre = "";
            var mi_apellido = "";

            // se recorre la fila
            for (var j in fila) 
            {
                // se obtiene el elemento
                var mi_elemento = fila[j];

                // se guarda el nombre completo
                if (j > 0 && j < 4) {
                    if (j == 1) mi_nombre += mi_elemento;
                    else mi_apellido += mi_elemento+" ";
                    if (j == 3) {
                        mi_fila.push("");
                    }
                }
                else if (j == 0) {
                    mi_fila.push(mi_elemento);
                } else {
                    if (mi_elemento == "1") {
                        mi_elemento = {text:"*",alignment:'center'};
                        mi_fila.push(mi_elemento);
                    }
                    else if (mi_elemento == "0") {
                        mi_elemento = {text:"",alignment:'center'};
                        mi_fila.push(mi_elemento);
                    }
                }
            }

            // se coloca el nombre
            mi_fila[1] = mi_apellido+mi_nombre;
            mi_tabla.push(mi_fila);
        }
        
        // se obtiene la asistencia calculada
        var tot_asis = $("#vista_footer p").text().replace("Asistencia : ","");

        // definicion del documento
        var docDefinition = { 
            header: {
                margin: 20,
                columns: [
                    {
                        text: "Asistencia "+tipo+$("#fecha").val(),
                        bold: true
                    },
                    {
                        text:"Porcentaje de asistencia: " +tot_asis,
                        alignment:"right",
                        bold:true
                    }
                ]
            },
            content: [
                {
                    style: 'style_table',
                    table: {
                        widths: ['auto', '*', 'auto'],
                        headerRows: 1,
                        body: mi_tabla
                    }
                }
            ],
            footer: function(page, pages) { 
                return { 
                    columns: [ 
                        '(*) Indica que si asistió al evento',
                        { 
                            alignment: 'right',
                            text: [
                                { text: page.toString(), italics: true },
                                ' / ',
                                { text: pages.toString(), italics: true }
                            ]
                        }
                    ],
                    margin: [20, 10]
                };
            },
            styles: {
                style_table: {
                    fontSize: 10,
                    margin: [0, 5, 0, 15]
                }
            }
        };

        // open the PDF in a new window
        pdfMake.createPdf(docDefinition).open();
    }
    // si se requiere consultar total anual por socia
    else if (estado == 3.2)
    {
        // tabla que se enviara a pdfmake para generar el documento
        var mi_tabla = [];

        // se obtiene el arreglo de elementos de la tabla actual
        var arr_tabla = tabla.rows().data().toArray();

        // se agregan las cabeceras a la tabla
        var mi_cabecera = [
            {text:"Rut",bold:true,fillColor:"#DBDBDB"},
            {text:"Nombre Completo",bold:true,fillColor:"#DBDBDB"},
            {text:"Porcentaje (%)",bold:true,fillColor:"#DBDBDB"},
            {text:"Eventos asistidos",bold:true,fillColor:"#DBDBDB"},
            {text:"Total eventos",bold:true,fillColor:"#DBDBDB"}
        ];
        mi_tabla.push(mi_cabecera);

        // se recorre el arreglo
        for (var i in arr_tabla) 
        {
            // se obtiene la fila
            var fila = arr_tabla[i];
            var mi_fila = [];

            // para unir en una columna todo el nombre
            var mi_nombre = "";
            var mi_apellido = "";

            // se recorre la fila
            for (var j in fila) 
            {
                // se obtiene el elemento
                var mi_elemento = fila[j];

                // se guarda el nombre completo
                if (j > 0 && j < 4) {
                    if (j == 1) mi_nombre += mi_elemento;
                    else mi_apellido += mi_elemento+" ";
                    if (j == 3) {
                        mi_fila.push("");
                    }
                }
                else if (j == 0) {
                    mi_fila.push(mi_elemento);
                } else {
                    mi_elemento = {text:mi_elemento,alignment:'center'};
                    mi_fila.push(mi_elemento);
                }
            }

            // se coloca el nombre
            mi_fila[1] = mi_apellido+mi_nombre;
            mi_tabla.push(mi_fila);
        }

        // definicion del documento
        var docDefinition = { 
            header: {
                margin: 20,
                columns: [
                    {
                        text: "Total asistencia año "+$("#total-año").val(),
                        bold: true
                    },
                    {
                        text:"Fecha: " +moment(new Date()).format("DD/MM/YYYY"),
                        alignment:"right",
                        bold:true
                    }
                ]
            },
            content: [
                {
                    style: 'style_table',
                    table: {
                        widths: ['auto', '*', 'auto','auto','auto'],
                        headerRows: 1,
                        body: mi_tabla
                    }
                }
            ],
            footer: function(page, pages) { 
                return { 
                    alignment: 'right',
                    text: [
                        { text: page.toString(), italics: true },
                        ' / ',
                        { text: pages.toString(), italics: true }
                    ],
                    margin: [20, 10]
                };
            },
            styles: {
                style_table: {
                    fontSize: 10,
                    margin: [0, 5, 0, 15]
                }
            }
        };

        // open the PDF in a new window
        pdfMake.createPdf(docDefinition).open();
    }
});

// el ingreso del año solo acepta numeros
$("#total-año").keypress(function(e){
    return false;
});

// cuando cambia el año, se restringe que salga de un rango
$("#total-año").on("input",function(){

    // se obtiene el nuevo valor
    var nuevo = parseInt($(this).val());

    // si el nuevo es mayor que el maximo, se le resta 1
    if (nuevo > año_actual) {
        nuevo = año_actual;
    }
    if (nuevo < 2000) {
        nuevo = 2000;
    }

    // se asigna el valor
    $("#total-año").val(nuevo);
});

// si se confirma el año
$("#total-confirmar").click(function(){

    var año = $("#total-año").val();
    if (año != "") {

        $(".form-group").removeClass("has-error");
        $("#total-año").css({"background-color":""});

        // se hace la peticion para modificar una asistencia por si hubo algun error
        $.ajax({
            type: "POST",
            url: url_global+"Evento/consultar_eventos_anual",
            data: {
                año: año,
                llave: true
            }
        }).done(function(response){

            // se obtiene el mensaje
            var mensaje = JSON.parse(response);

            // si el mensaje es exitoso
            if (mensaje[0] == 1) {

                // se limpia la tabla
                tabla.clear();

                // se recorre el arreglo
                for (var i in mensaje[1]) {
                    var socia = mensaje[1][i];
                    tabla.row.add(socia);
                }

                // se visualiza el contenido de la tabla
                $("#tabla tbody").show();

                // se dibuja la tabla
                tabla.draw();

                $("#MantConfirmar").show();
                $("#MantConfirmar").text("Generar Informe");

            } else {
                mensaje_error(mensaje[1]);
            }
        });

    } else {
        $(this).parents(".form-group").addClass("has-error");
        $("#total-año").css({"background-color":"#FDCDCD"});
        $("#vista_footer p").text("Ingrese el año de consulta");
    }

});