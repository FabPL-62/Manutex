// variables de entorno-----------------------------------------------------------------------------
// inputs requeridos solo en los estados 1,2
var required = [
    "evento_fecha"
];

// 1: agregar
// 2: modificar
// 3: consultar
// 4: eliminar
var estado = 1;

// obtener alto para la tabla
function alto_tabla() 
{
    if (estado == 4) return $("#vista_contenido").height()-100;
    else if (estado == 3) return $("#vista_contenido").height()-180;
    else return Math.max($("#vista_contenido").height()-$(".formulario").height()-100,250);
}

// se obtiene el alto inicial del formulario
var formulario_h = $("#vista_contenido .formulario").height();

var tabla;
var fila_select;
var fila_index = -1;
$(document).ready(function(){

    $('#tabla tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" style="width:inherit;" placeholder="Buscar por '+title+'" />' );
    });
   
    // se carga la forma como datatable
    tabla = $('#tabla').DataTable({
        "scrollY": alto_tabla(),
        "scrollX": true,
        "paging": false,
        "searching": true,
        "info": false,
        "ordering" : false,
        "aaSorting": [[ 0, "asc" ]],
        "language": {
            "emptyTable": "No hay datos disponibles",
            "zeroRecords" : "No hay coincidencias"
        }
    });

    // se ejecuta para mostrar el contenido de la tabla
    $(function(){
        $("#tabla-content").removeAttr("hidden");
        $("#tabla_filter").remove();
        tabla.draw();
    });
    
    $('#tabla tbody').on( 'click', 'tr', function () {
        if (estado > 1 && estado != 3) {
            
            $("#tabla tbody tr").removeClass('selected');
            $(this).toggleClass('selected');
            fila_select = tabla.row(this).data();
            fila_index = tabla.row(this).index();

            // si se quiere modificar, se actualizan los inputs
            if (estado == 2)
            {
                $("#evento_fecha").val(fila_select[0]);

                // se obtiene el contenido del div de descripcion
                var dd = $.parseHTML(fila_select[2]);
                $("#evento_desc").val($(dd).text());

                var valor = "T";
                if (fila_select[1] == "Reunión Ordinaria") valor = "O";
                if (fila_select[1] == "Reunión Extraordinaria") valor = "E";
                $("#evento_tipo").val(valor);

                $(".formulario .form-control").prop("disabled",false);
                $("#evento_fecha").prop("disabled",true);

                $("#vista_footer p").text("El evento esta listo para modificar");
                $("#MantConfirmar").show();
            }
            if (estado == 4) {
                $("#vista_footer p").text("El evento esta listo para eliminar");
                $("#MantConfirmar").show();
            }
        }
    });

    tabla.columns().every( function () {
        var that = this;
 
        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        });
    });
});

// cuando se redimensiona la ventana del navegador
$(window).resize(function(){
    $("div.dataTables_scrollBody").height(alto_tabla());
});

// cambio de estados al seleccionar opciones--------------------------------------------------------
// cuando se presiona agregar
$("#MantAgregar").click(function(){
    if (estado != 1) {
        $("#vista_contenido .formulario .form-group").removeClass("has-error");
        $("#vista_contenido .formulario input").css({"background-color":""});
        $("#vista_header p").text("Evento : Agregar");
        $("#vista_footer p").html("Complete todos los campos requeridos <b>(*)</b>");
        $(".formulario .form-control").prop("disabled",false);
        $("#vista_contenido .formulario").show();
        $("#vista_contenido .formulario").height(formulario_h);
        $("#tabla tbody tr").show();
        $(".fila-general").show();
        $(".fila-consulta").hide();
        $("#tabla tbody tr").removeClass('selected');
        $("#MantConfirmar").text("Confirmar");
        $("#MantConfirmar").show();
        $(".formulario input").val("");
        $(".formulario textarea").val("");
        estado = 1;
        $("#evento_rut").focus();
        $("div.dataTables_scrollBody").height(alto_tabla());
        $("#evento_fecha").data('daterangepicker').setStartDate(moment(new Date()).format('DD/MM/YYYY HH:mm'));
    }
});
// cuando se presiona modificar
$("#MantModificar").click(function(){
    if (estado != 2) {
        $("#vista_contenido .formulario .form-group").removeClass("has-error");
        $("#vista_contenido .formulario input").css({"background-color":""});
        $("#vista_header p").text("Evento : Modificar");
        $("#vista_footer p").text("Seleccione un elemento en la tabla");
        $(".formulario input").val("");
        $(".formulario textarea").val("");
        $(".formulario .form-control").prop("disabled",true);
        $("#vista_contenido .formulario").show();
        $("#vista_contenido .formulario").height(formulario_h);
        $("#tabla tbody tr").show();
        $(".fila-general").show();
        $(".fila-consulta").hide();
        $("#tabla tbody tr").removeClass('selected');
        $("#MantConfirmar").text("Confirmar");
        $("#MantConfirmar").hide();
        estado = 2;
        $("div.dataTables_scrollBody").height(alto_tabla());
    }
});
// cuando se presiona consultar
$("#MantConsultar").click(function(){
    $("#tabla tbody tr").show();
    if (estado != 3) {
        $("#vista_contenido .formulario .form-group").removeClass("has-error");
        $("#vista_contenido .formulario input").css({"background-color":""});
        $("#vista_header p").text("Evento : Consultar");
        $("#vista_footer p").text("Llene los campos para realizar una busqueda");
        $("#vista_contenido .formulario").show();
        $("#vista_contenido .formulario").height(80);
        $(".fila-general").hide();
        $(".fila-consulta").show();
        $("#rango_fechas").prop("disabled",false);
        $("#tabla tbody tr").removeClass('selected');
        $("#MantConfirmar").text("Generar Informe");
        $("#MantConfirmar").show();
        estado = 3;
        $("div.dataTables_scrollBody").height(alto_tabla());
    }
});
// cuando se presiona eliminar
$("#MantEliminar").click(function(){
    if (estado != 4) {
        $("#vista_contenido .formulario .form-group").removeClass("has-error");
        $("#vista_contenido .formulario input").css({"background-color":""});
        $("#vista_header p").text("Evento : Eliminar");
        $("#vista_footer p").text("Seleccione un elemento en la tabla");
        $("#tabla tbody tr").show();
        $("#vista_contenido .formulario").hide();
        $("#tabla tbody tr").removeClass('selected');
        $("#MantConfirmar").text("Confirmar");
        $("#MantConfirmar").hide();
        estado = 4;
        $("div.dataTables_scrollBody").height(alto_tabla());
    }
});

// quitar el borde de alerta------------------------------------------------------------------------
$("#vista_opciones button").click(function(){
    $("#vista_contenido .formulario .form-group").removeClass("has-error");
});

// confirmar operaciones----------------------------------------------------------------------------
$("#MantConfirmar").click(function(e){
    
    $("#vista_contenido .formulario .form-group").removeClass("has-error");
    $("#vista_contenido .formulario .form-control").css({"background-color":""});
   
    // confirmar agregar
    if (estado == 1)
    {
        // se recorren los inputs
        var primero = "";
        var existe = false;
        $("#vista_contenido .formulario .form-control").each(function(i,v){
            var input_text = $(this).val();
            var input_id   = $(this).attr("id");
            existe = false;
            if (input_text == "") {
                for (var j = 0; j < required.length; j++) {
                    if (input_id == required[j]) {
                        existe = true;
                    }
                }
            }
            if (existe == true)
            {
                if (primero == "") primero = "#"+input_id;
                $(this).parents(".form-group").addClass("has-error");
                $(this).css({"background-color":"#FDCDCD"});
            }
        });

        // si no se encuentra un primero
        if (primero == "")
        {
            // se obtienen los valores de los campos
            var fecha = $("#evento_fecha").val();
            var tipo = $("#evento_tipo option:selected").val();
            var descripcion = $("#evento_desc").val();
            
            // se formatea la fecha para mysql
            var fecha_hora = fecha.split(" ");
            var fecha_array = fecha_hora[0].split("/");
            fecha = fecha_array[2]+"-"+fecha_array[1]+"-"+fecha_array[0];
            fecha = fecha + " " + fecha_hora[1]+":00";

            // se hace la peticion ajax para agregar
            $.ajax({        
                type: "POST",
                url: url_global+"Evento/agregar",
                data: {
                    evento_fecha: fecha,
                    evento_tipo: tipo,
                    evento_desc: descripcion,
                    llave: true                   
                }
            }).done(function(response){
                if (response == 1){
                    $("#vista_footer p").text("El evento se ingreso exitosamente en el sistema");

                    var tipo_text = "Taller";
		            if (tipo == "O") tipo_text = "Reunión Ordinaria";
		            if (tipo == "E") tipo_text = "Reunión Extraordinaria";

                    fecha = $("#evento_fecha").val();
                    var dd = "<div style='max-height:70px;overflow-y:auto;'>"+descripcion+"</div>";
                    tabla.row.add([
                        fecha,
                        tipo_text,
                        dd
                    ]).draw(false);
                }
                else{
                    mensaje_error(response);
                }
            });
        }
        else
        {
            // se enfoca el primer input que no se ha completado
            $(primero).focus();

            // se muestra el mensaje
            $("#vista_footer p").text("Recuerde completar todos los campos requeridos (*)");
        }
    }
    // confirmar modificar
    else if (estado == 2)
    {   
        // se recorren los inputs
        var primero = "";
        var existe = false;
        $("#vista_contenido .formulario input").each(function(i,v){
            var input_text = $(this).val();
            var input_id   = $(this).attr("id");
            existe = false;
            if (input_text == "") {
                for (var j = 0; j < required.length; j++) {
                    if (input_id == required[j]) {
                        existe = true;
                    }
                }
            }
            if (existe == true)
            {
                if (primero == "") primero = "#"+input_id;
                $(this).parents(".form-group").addClass("has-error");
                $(this).css({"background-color":"#FDCDCD"});
            }
        });

        // si no se encuentra un primero
        if (primero == "")
        {
            mensaje_confirmar(
                "Confirmar modificación",
                "Esta segura de modificar el evento con fecha "+fila_select[0]+" ?",
            function(){
                // se obtienen los valores de los campos
                var fecha = $("#evento_fecha").val();
                var tipo = $("#evento_tipo option:selected").val();

                var tipo_text = "Taller";
                if (tipo == "O") tipo_text = "Reunión Ordinaria";
                if (tipo == "E") tipo_text = "Reunión Extraordinaria";

                var descripcion = $("#evento_desc").val();
                var dd = "<div style='max-height:70px;overflow-y:auto;'>"+descripcion+"</div>";

                // se actualizan los datos en la tabla
                fila_select[1] = tipo_text;
                fila_select[2] = dd;
                tabla.row(fila_index).data(fila_select).draw();
                
                // se formatea la fecha para mysql
                var fecha_hora = fecha.split(" ");
                var fecha_array = fecha_hora[0].split("/");
                fecha = fecha_array[2]+"-"+fecha_array[1]+"-"+fecha_array[0];
                fecha = fecha + " " + fecha_hora[1]+":00";

                // se hace la peticion ajax para modificar
                $.ajax({        
                    type: "POST",
                    url: url_global+"Evento/modificar",
                    data: {
                        evento_fecha: fecha,
                        evento_tipo: tipo,
                        evento_desc: descripcion,
                        llave: true                
                    }
                }).done(function(response){
                    if (response == 1) $("#vista_footer p").text("Se ha realizado la modificación exitosamente");
                    else $("#vista_footer p").text(response);
                });
            },function(){
                $("#vista_footer p").text("se canceló la operación de modificación");
            });
        }
        else
        {
            // se enfoca el primer input que no se ha completado
            $(primero).focus();

            // se muestra el mensaje
            $("#vista_footer p").text("Recuerde completar todos los campos requeridos (*)");
        }
    }
    // generar informe de consulta
    else if (estado == 3)
    {
        // se genera el arreglo que se envia a pdfmake
        var doc_array = [];

        // se recorren todos las filas que estan activas
        // y se agregan a doc_array
        $("#tabla tbody tr").each(function(i,v){
            var visible = $(this).is(":visible");
            if (visible) {

                // se obtiene la data de la fila
                var data = tabla.row(this).data();

                // debe existir data
                if (data != undefined)
                {
                    // se obtiene la cabecera
                    var head = {
                        text : [
                            {text:data[1],bold:true},
                            " del ",
                            {text:data[0],bold:true}
                        ],
                        fillColor:"#DBDBDB"
                    };

                    // se obtiene la descripcion
                    var desc = $($.parseHTML(data[2])).text();

                    // objeto que contiene la tabla
                    var doc_tabla = {
                        style: 'style_table',
                        table: {
                            widths: ["100%"],
                            //headerRows: 1,
                            body: [[head],[{text:desc,alignment:'justify'}]]
                        }
                    }
                    doc_array.push(doc_tabla);
                }
            }
        });

        if (doc_array.length > 0)
        {
            // definicion del documento
            var docDefinition = { 
                header: {
                    margin: 20,
                    columns: [
                        {
                            text: "Informe de Eventos",
                            bold: true
                        },
                        {
                            text: "Fecha : "+moment(new Date()).format('DD/MM/YYYY'),
                            alignment: "right",
                            bold: true
                        }
                    ]
                },
                content: doc_array,
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
        else
        {
            $("#vista_footer p").text("No hay eventos seleccionados para generar el informe");
        }
    }
    // confirmar eliminar
    else if (estado == 4)
    {
        // se pregunta si realmente se quiere eliminar
        mensaje_confirmar("Confirmar eliminación","Esta seguro que desea eliminar el evento con fecha "+fila_select[0]+" ?",
        function(){

            // se obtienen los valores de los campos
            var fecha = fila_select[0];

            // se formatea la fecha para mysql
            var fecha_hora = fecha.split(" ");
            var fecha_array = fecha_hora[0].split("/");
            fecha = fecha_array[2]+"-"+fecha_array[1]+"-"+fecha_array[0];
            fecha = fecha + " " + fecha_hora[1]+":00";

            // se hace la peticion ajax para eliminar
            $.ajax({        
                type: "POST",
                url: url_global+"Evento/eliminar",
                data: {
                    evento_fecha: fecha,
                    llave: true                 
                }
            }).done(function(response){

                // obtenemos el arreglo de mensaje
                var mensaje = JSON.parse(response);

                if (mensaje[0] == 1){
                    $("#vista_footer p").text("El evento se eliminó exitosamente del sistema");
                    tabla.row(fila_index).remove().draw();
                }
                else
                {
                    mensaje_error(mensaje[1]);
                }
            });

        },function(){
            $("#vista_footer p").text("Se canceló la operación de eliminación");
        });
    }
});

//date picker
$(function() {
    $('#evento_fecha').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        timePicker:true,
        timePicker24Hour:true,
        locale: {
            firstDay: 1,
            format: 'DD/MM/YYYY HH:mm',
            applyLabel:"Confirmar",
            cancelLabel:"Cancelar",
            daysOfWeek: [
                "Do",
                "Lu",
                "Ma",
                "Mi",
                "Ju",
                "Vi",
                "Sa"
                ],                
            "monthNames": [
                "Enero",
                "Febrero",
                "Marzo",
                "Abril",
                "Mayo",
                "Junio",
                "Julio",
                "Agosto",
                "Septiembre",
                "Octubre",
                "Noviembre",
                "Deciembre"
        ]
        }
        
    });
    $("#evento_fecha").data('daterangepicker').setStartDate(moment(new Date()).format('DD/MM/YYYY HH:mm'));
    $(".range_inputs").remove();
    $('#rango_fechas').daterangepicker({
        autoUpdateInput: false,
        showDropdowns: true,
        maxDate: new Date(),
        locale: {
            firstDay: 1,
            format: 'DD/MM/YYYY',
            applyLabel:"Confirmar",
            cancelLabel:"Cancelar",
            daysOfWeek: [
                "Do",
                "Lu",
                "Ma",
                "Mi",
                "Ju",
                "Vi",
                "Sa"
                ],                
            "monthNames": [
                "Enero",
                "Febrero",
                "Marzo",
                "Abril",
                "Mayo",
                "Junio",
                "Julio",
                "Agosto",
                "Septiembre",
                "Octubre",
                "Noviembre",
                "Deciembre"
        ]
        }
        
    });
    // cuando se selecciona un rango de fechas en la consulta -------------------------------------------
    $('#rango_fechas').on('apply.daterangepicker', function(ev, picker) {

        var start = picker.startDate.format('DD/MM/YYYY');
        var end = picker.endDate.format('DD/MM/YYYY');

        if (start != end) {
            $(this).val(start+" - "+end);
        } else {
            $(this).val(start);
        }
        
        $("#tabla tbody tr").show();
        $("#tabla tbody tr").each(function(i,v){

            // se obtiene sólo la fecha definida en la fila [dd,mm,aaaa]
            var fecha_arr = $(this).children("td:first-child").text().split(" ")[0].split("/");

            // se formatea la fecha a yyyy-mm-dd y se convierte a Date
            var fecha = moment(fecha_arr[2]+"-"+fecha_arr[1]+"-"+fecha_arr[0]).toDate();
            
            // se obtienen los rangos de fechas
            var fecha_min = moment(picker.startDate).toDate();
            var fecha_max = moment(picker.endDate).toDate();

            if (fecha < fecha_min || fecha > fecha_max) {
                $(this).hide();
            }
            
        });
    });
    // falta colocar un boton para volver a ver todos los eventos
    $('#rango_fechas').on('hide.daterangepicker', function(ev, picker) {
        
        // se ve si hay algo escrito dentro
        var actual = $(this).val();
        if (actual != "") {
            var arreglo = actual.split("-");
            if (arreglo.length == 2) {
                arreglo[0] = arreglo[0].trim();
                arreglo[1] = arreglo[1].trim();
                if (fecha_valida(arreglo[0],"DD/MM/YYYY") == false || fecha_valida(arreglo[1],"DD/MM/YYYY") == false) {
                    $("#tabla tbody tr").show();
                    $(this).val("");
                }
            } else if (arreglo.length == 1) {
                if (fecha_valida(actual,"DD/MM/YYYY") == false) {
                    $("#tabla tbody tr").show();
                    $(this).val("");
                }
            } else {
                $("#tabla tbody tr").show();
                $(this).val("");
            }
            
        } else {
            $("#tabla tbody tr").show();
            $(this).val("");
        }

    });
});
