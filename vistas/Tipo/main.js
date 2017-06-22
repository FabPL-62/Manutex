// variables de entorno-----------------------------------------------------------------------------
// inputs requeridos solo en los estados 1,2
var required = [
    "tipo",
    "descripcion"
];

// 1: agregar
// 2: modificar
// 3: consultar
// 4: eliminar
var estado = 1;

// obtener alto para la tabla
function alto_tabla() 
{
    if (estado >= 3) return $("#vista_contenido").height()-100;
    else return Math.max($("#vista_contenido").height()-$(".formulario").height()-100,250);
}

var tabla;
var fila_select;
var fila_index = -1;
$(document).ready(function(){

    $('#tabla tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" style="width:inherit;" placeholder="Buscar por '+title+'" />' );
    } );

    $("table.dataTable.hover tbody tr:hover").css({"background-color":"#C2C2C2"});
   
    // se carga la forma como datatable
    tabla = $('#tabla').DataTable({
        "scrollY": alto_tabla(),
        "scrollX": true,
        "paging": false,
        "searching": true,
        "info": false,
        "language": {
            "emptyTable": "No hay datos disponibles",
            "zeroRecords" : "No hay coincidencias"
        }
    });

    // se ejecuta para mostrar el contenido de la tabla
    $(function(){
        $("#tabla-content").removeAttr("hidden");
        tabla.draw();
    });

    $("#tabla_filter").remove();
    
    $('#tabla tbody').on( 'click', 'tr', function () {
        if (estado > 1 && estado != 3) {
            
            $("#tabla tbody tr").removeClass('selected');
            $(this).toggleClass('selected');
            fila_select = tabla.row(this).data();
            fila_index = tabla.row(this).index();

            // si se quiere modificar, se actualizan los inputs
            if (estado == 2)
            {
                $("#tipo").val(fila_select[0]);
                $("#descripcion").val(fila_select[1]);
                $("#cantidad").val(fila_select[2]);

                $(".formulario .form-control").prop("disabled",false);
                $("#tipo").prop("disabled",true);
                $("#cantidad").prop("disabled",true);

                $("#vista_footer p").text("El tipo de máquina ya esta listo para modificar");
                $("#MantConfirmar").show();
            }
            if (estado == 4) {
                $("#vista_footer p").text("El tipo de máquina ya esta listo para eliminar");
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
        $("#vista_header p").text("Tipo de Máquina : Agregar");
        $("#vista_footer p").html("Complete todos los campos requeridos <b>(*)</b>");
        $(".formulario .form-control").prop("disabled",false);
        $("#cantidad").prop("disabled",true);
        $("#vista_contenido .formulario").show();
        $("#tabla tbody tr").removeClass('selected');
        $("#MantConfirmar").text("Confirmar");
        $("#MantConfirmar").show();
        $(".formulario input").val("");
        $("#cantidad").val("0");
        estado = 1;
        $("#tipo").focus();
        $("div.dataTables_scrollBody").height(alto_tabla());
    }
});
// cuando se presiona modificar
$("#MantModificar").click(function(){
    if (estado != 2) {
        $("#vista_contenido .formulario .form-group").removeClass("has-error");
        $("#vista_contenido .formulario input").css({"background-color":""});
        $("#vista_header p").text("Tipo de Máquina : Modificar");
        $("#vista_footer p").text("Seleccione un elemento en la tabla");
        $(".formulario input").val("");
        $("#cantidad").val("0");
        $(".formulario .form-control").prop("disabled",true);
        $("#vista_contenido .formulario").show();
        $("#tabla tbody tr").removeClass('selected');
        $("#MantConfirmar").text("Confirmar");
        $("#MantConfirmar").hide();
        estado = 2;
        $("div.dataTables_scrollBody").height(alto_tabla());
    }
});
// cuando se presiona consultar
$("#MantConsultar").click(function(){
    if (estado != 3) {
        $("#vista_contenido .formulario .form-group").removeClass("has-error");
        $("#vista_contenido .formulario input").css({"background-color":""});
        $("#vista_header p").text("Tipo de Máquina : Consultar");
        $("#vista_footer p").text("Llene los campos para realizar una busqueda");
        $("#vista_contenido .formulario").hide();
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
        $("#vista_header p").text("Tipo de Máquina : Eliminar");
        $("#vista_footer p").text("Seleccione un elemento en la tabla");
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
            var tipo = $("#tipo").val();
            var descripcion = $("#descripcion").val();
            var cantidad = $("#cantidad").val();

            // se hace la peticion ajax para agregar
            $.ajax({        
                type: "POST",
                url: url_global+"TipoMaquina/agregar",
                data: {
                    tipo_codigo: tipo,
                    tipo_descripcion: descripcion,
                    tipo_cantidad: cantidad,
                    llave: true             
                }
            }).done(function(response){
                var mensaje = JSON.parse(response);
                if (mensaje[0] == 1){
                    $("#vista_footer p").text(mensaje[1]);
                    tabla.row.add([
                        tipo,
                        descripcion,
                        cantidad
                    ]).draw(false);
                }
                else{
                    mensaje_error(mensaje[1]);
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
                "Esta segura de modificar la descripción de la máquina "+fila_select[0]+" ?",
            function(){
                // se obtienen los valores de los campos
                var tipo = $("#tipo").val();
                var descripcion = $("#descripcion").val();
                var cantidad = $("#cantidad").val();

                // se actualizan los datos en la tabla
                fila_select[1] = descripcion;
                fila_select[2] = cantidad;
                tabla.row(fila_index).data(fila_select).draw();

                // se hace la peticion ajax para modificar
                $.ajax({        
                    type: "POST",
                    url: url_global+"TipoMaquina/modificar",
                    data: {
                        tipo_codigo: tipo,
                        tipo_descripcion: descripcion,
                        tipo_cantidad: cantidad,
                        llave: true             
                    }
                }).done(function(response){
                    var mensaje = JSON.parse(response);
                    if (mensaje[0] == 1) $("#vista_footer p").text(mensaje[1]);
                    else mensaje_error(mensaje[1]);
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
        // tabla que se enviara a pdfmake para generar el documento
        var mi_tabla = [];

        // arreglo de anchos de las columnas
        var mi_anchos = ["auto","*","auto"];

        // se agregan las cabeceras a la tabla
        var mi_cabecera = [
            {text:"Código de Máquina",bold:true,fillColor:"#DBDBDB"},
            {text:"Descripción",bold:true,fillColor:"#DBDBDB"},
            {text:"Cantidad",bold:true,fillColor:"#DBDBDB"}
        ];

        // se agregan las cabeceras a la tabla
        mi_tabla.push(mi_cabecera);

        // se recorren las filas
        $("#tabla tbody tr").each(function(i,v){
            var visible = $(this).is(":visible");
            if (visible) {

                // se obtiene la data de la fila
                var data = tabla.row(this).data();

                // debe existir data
                if (data != undefined)
                {
                    // para obtener los datos de la fila
                    var mi_fila = [];

                    // se recorren los elementos de la fila
                    $(this).find('td').each(function(ii,vv){

                        var mi_elemento = $(this).text();
                        mi_fila.push(mi_elemento);

                    });

                    mi_tabla.push(mi_fila);
                }
            }
        });

        if (mi_tabla.length > 1)
        {

            // definicion del documento
            var docDefinition = { 
                header: {
                    margin: 20,
                    columns: [
                        {
                            text: "Listado de Tipos de Máquinas",
                            bold: true
                        },
                        {
                            text: "Fecha : "+moment(new Date()).format('DD/MM/YYYY'),
                            alignment: "right",
                            bold: true
                        }
                    ]
                },
                content: [
                    {
                        style: 'style_table',
                        table: {
                            widths: mi_anchos,
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
        else 
        {
            $("#vista_footer p").text("No hay tipos de máquinas seleccionadas para generar el informe");
        }
    }
    // confirmar eliminar
    else if (estado == 4)
    {
        // se pregunta si realmente se quiere eliminar
        mensaje_confirmar(
            "Confirmar eliminación",
            "Esta segura que desea eliminar el tipo de máquina "+fila_select[0]+" ?",
        function(){

            // se obtienen los valores de los campos
            var tipo = fila_select[0];

            // se hace la peticion ajax para eliminar
            $.ajax({        
                type: "POST",
                url: url_global+"TipoMaquina/eliminar",
                data: {
                    tipo_codigo: tipo,
                    llave: true              
                }
            }).done(function(response){
                var mensaje = JSON.parse(response);
                if (mensaje[0] == 1){
                    $("#vista_footer p").text(mensaje[1]);
                    tabla.row(fila_index).remove().draw();
                }
                else{
                    mensaje_error(mensaje[1]);
                }
            });
        },function(){
            $("#vista_footer p").text("se canceló la operación de eliminación");
        });
    }
});