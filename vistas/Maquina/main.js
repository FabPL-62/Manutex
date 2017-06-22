// variables de entorno-----------------------------------------------------------------------------
// inputs requeridos solo en los estados 1,2
var required = [
    "maq_modelo",
    "maq_marca"
];

// 1: agregar
// 2: modificar
// 3: consultar
// 4: eliminar
var estado = 1;

// obtener alto para la tabla segun estado
function alto_tabla() 
{
    if (estado > 3) return $("#vista_contenido").height()-100;
    else return Math.max($("#vista_contenido").height()-$(".formulario").height()-100,250);
}

// se obtiene el alto inicial del formulario
var formulario_h = $("#vista_contenido .formulario").height();

// variable para guardar la tabla
var tabla;

// variables para obtener la fila seleccionada
var fila_select;
var fila_index = -1;

// inicializacion de la tabla
$(document).ready(function(){

    // agrega inputs de busqueda
    $('#tabla tfoot th').each(function(){
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
        "aaSorting": [],
        "language": {
            "emptyTable": "No hay datos disponibles",
            "zeroRecords" : "No hay coincidencias"
        }
    });

    // se ejecuta para mostrar el contenido de la tabla
    $(function(){
        $("#tabla-content").show();
        $("#tabla_filter").remove();
        tabla.draw();
    });
    
    // si se hace click sobre una fila
    $('#tabla tbody').on( 'click', 'tr', function () {

        // si el estado es modificar o eliminar
        if (estado == 2 || estado == 4) {
            
            // se limpian todas las filas previamente seleccionadas
            $("#tabla tbody tr").removeClass('selected');

            // se selecciona la fila
            $(this).toggleClass('selected');

            // se obtiene los datos de la fila seleccionada
            fila_select = tabla.row(this).data();
            fila_index = tabla.row(this).index();

            // si se quiere modificar, se actualizan los inputs
            if (estado == 2)
            {
                var tipo = "CO";
                if (fila_select[0] == "Overlock") tipo = "OV";
                if (fila_select[0] == "Máquina de Coser Doméstica") tipo = "MC";
                $("#maq_tipo").val(tipo);

                $("#maq_num").val(fila_select[1]);
                $("#maq_marca").val(fila_select[2]);
                $("#maq_modelo").val(fila_select[3]);

                var valor = "O";
                if (fila_select[4] == "Defectuosa") valor = "D";
                if (fila_select[4] == "En Mantención") valor = "M";
                $("#maq_estado").val(valor);

                $("#maq_ingreso").val(fila_select[5]);
                $("#maq_mantencion").val(fila_select[6]);
                $("#maq_uso").val(fila_select[7]);

                $(".formulario .form-control").prop("disabled",false);
                $("#maq_tipo").prop("disabled",true);
                $("#maq_num").prop("disabled",true);
                $("#maq_uso").prop("disabled",true);

                $("#vista_footer p").text("La máquina ya esta lista para modificar");
                $("#MantConfirmar").show();
            }

            // si se quiere eliminar, se cambia el mensaje
            else if (estado == 4) {
                $("#vista_footer p").text("La máquina esta lista para eliminar");
                $("#MantConfirmar").show();
            }
        }
    });

    // realizar busqueda incremental por cada input de busqueda de la tabla
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

// para ver el contenido de un dropdown fuera del contenedor
$('.dropdown-toggle').click(function (){
    dropDownFixPosition($(this),$(this).parent().children(".dropdown-menu"));
});
function dropDownFixPosition(button,dropdown){
    var dropDownTop = button.offset().top + button.outerHeight();
    dropdown.css('top',dropDownTop+"px");
    dropdown.css('left',button.offset().left+"px");
}
$(".dropdown .dropdown-menu li a").click(function(e){
    e.stopPropagation();
    var mi_span = $(this).find("span");
    var mi_opcion = $(this).find(".vis-opcion").text();
    var mi_columna = $(this).attr("columna");
    if (mi_span.hasClass("glyphicon-eye-open")) {

        mi_span.removeClass("glyphicon-eye-open");
        mi_span.addClass("glyphicon-eye-close");

        var column = tabla.column(mi_columna);
        column.visible(false);

    } else {

        mi_span.removeClass("glyphicon-eye-close");
        mi_span.addClass("glyphicon-eye-open");

        var column = tabla.column(mi_columna);
        column.visible(true);
    }
});

// cambio de estados al seleccionar opciones--------------------------------------------------------
// cuando se presiona agregar
$("#MantAgregar").click(function(){

    // solo se ejecuta si el estado actual es distinto
    if (estado != 1) {

        // se borra la apariencia de error en los inputs
        $("#vista_contenido .formulario .form-group").removeClass("has-error");
        $("#vista_contenido .formulario input").css({"background-color":""});

        // se cambia el estado de la vista
        $("#vista_header p").text("Máquina : Agregar");
        $("#vista_footer p").html("Complete todos los campos requeridos <b>(*)</b>");
        estado = 1;

        // se habilitan todos los inputs y se vuelven a desabilitar algunos
        $(".formulario .form-control").prop("disabled",false);
        $("#maq_num").prop("disabled",true);
        $("#maq_uso").prop("disabled",true);

        // se hacen todas las columnas visibles
        $(".dropdown-menu li a span").removeClass('glyphicon-eye-close');
        $(".dropdown-menu li a span").addClass('glyphicon-eye-open');
        tabla.columns().visible(true);

        // se muestra el formulario
        $("#vista_contenido .formulario").show();
        $(".fila-general").show();
        $(".fila-consulta").hide();
        $("#vista_contenido .formulario").height(formulario_h);

        // se deseleccionan todas las filas
        $("#tabla tbody tr").removeClass('selected');

        // se muestra el boton de confirmacion
        $("#MantConfirmar").text("Confirmar");
        $("#MantConfirmar").show();

        // se limpian algunos inputs
        $("#maq_modelo").val("");
        $("#maq_marca").val("");
        $("#maq_mantencion").val("");

        // se obtiene la cantidad de tipos de maquina + 1 para agregar
        var maq_tipo = $("#maq_tipo option:selected").val();
        for (var i=0; i<tipos_post.length; i++) {
        	if (tipos_post[i][0] == maq_tipo) {
        		$("#maq_num").val(parseInt(tipos_post[i][2])+1);
        	}
        }

        // se enfoca la seleccion del tipo de maquina
        $("#maq_tipo").focus();

        // se redimensiona la tabla
        $("div.dataTables_scrollBody").height(alto_tabla());
    }
});

// cuando se presiona modificar
$("#MantModificar").click(function(){

    // solo se ejecuta si el estado actual es distinto
    if (estado != 2) {

        // se borra la apariencia de error en los inputs
        $("#vista_contenido .formulario .form-group").removeClass("has-error");
        $("#vista_contenido .formulario input").css({"background-color":""});

        // se cambia el estado de la vista
        $("#vista_header p").text("Máquina : Modificar");
        $("#vista_footer p").text("Seleccione un elemento en la tabla");
        estado = 2;

        // se deshabilitan todos los inputs
        $(".formulario .form-control").prop("disabled",true);

        // se hacen todas las columnas visibles
        $(".dropdown-menu li a span").removeClass('glyphicon-eye-close');
        $(".dropdown-menu li a span").addClass('glyphicon-eye-open');
        tabla.columns().visible(true);

        // se muestra el formulario        
        $("#vista_contenido .formulario").show();
        $(".fila-general").show();
        $(".fila-consulta").hide();
        $("#vista_contenido .formulario").height(formulario_h);

        // se deseleccionan todas las filas
        $("#tabla tbody tr").removeClass('selected');

        // se oculta el boton de confirmacion
        $("#MantConfirmar").text("Confirmar");
        $("#MantConfirmar").hide();
        
        // se redimensiona la tabla
        $("div.dataTables_scrollBody").height(alto_tabla());
    }
});

// cuando se presiona consultar
$("#MantConsultar").click(function(){

    // solo se ejecuta si el estado actual es distinto
    if (estado != 3) {

        // se borra la apariencia de error en los inputs
        $("#vista_contenido .formulario .form-group").removeClass("has-error");
        $("#vista_contenido .formulario input").css({"background-color":""});

        // se cambia el estado de la vista
        $("#vista_header p").text("Máquina : Consultar");
        $("#vista_footer p").text("Llene los campos para realizar una busqueda");
        estado = 3;

        // se hacen todas las columnas visibles
        $(".dropdown-menu li a span").removeClass('glyphicon-eye-close');
        $(".dropdown-menu li a span").addClass('glyphicon-eye-open');
        tabla.columns().visible(true);

        // se muestra el formulario de consulta y se oculta el general
        $("#vista_contenido .formulario").show();
        $(".fila-general").hide();
        $(".fila-consulta").show();
        $("#vista_contenido .formulario").height(60);

        // se habilitan todos los inputs
        $(".formulario .form-control").prop("disabled",false);

        // se deseleccionan todas las filas
        $("#tabla tbody tr").removeClass('selected');

        // se muestra el boton para generar informe
        $("#MantConfirmar").text("Generar Informe");
        $("#MantConfirmar").show();

        // se redimensiona la tabla
        $("div.dataTables_scrollBody").height(alto_tabla());
    }
});
// cuando se presiona eliminar
$("#MantEliminar").click(function(){

    // solo se ejecuta si el estado actual es distinto
    if (estado != 4) {

        // se borra la apariencia de error en los inputs
        $("#vista_contenido .formulario .form-group").removeClass("has-error");
        $("#vista_contenido .formulario input").css({"background-color":""});

        // se cambia el estado de la vista
        $("#vista_header p").text("Máquina : Eliminar");
        $("#vista_footer p").text("Seleccione un elemento en la tabla para eliminar");
        estado = 4;

        // se hacen todas las columnas visibles
        $(".dropdown-menu li a span").removeClass('glyphicon-eye-close');
        $(".dropdown-menu li a span").addClass('glyphicon-eye-open');
        tabla.columns().visible(true);

        // se oculta el formulario
        $("#vista_contenido .formulario").hide();

        // se deseleccionan todas las filas
        $("#tabla tbody tr").removeClass('selected');

        // se oculta el boton de confirmacion
        $("#MantConfirmar").text("Confirmar");
        $("#MantConfirmar").hide();
        
        // se redimensiona la tabla
        $("div.dataTables_scrollBody").height(alto_tabla());
    }
});

// confirmar operaciones----------------------------------------------------------------------------
$("#MantConfirmar").click(function(e){
    
    // se borra la apariencia de error en los inputs
    $("#vista_contenido .formulario .form-group").removeClass("has-error");
    $("#vista_contenido .formulario .form-control").css({"background-color":""});
    
    // ---------------------------------------------------------------------------------------------
    // confirmar agregar
    if (estado == 1)
    {
        // se recorren los inputs para saber cual es requerido
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
            var maq_tipo = $("#maq_tipo option:selected").val();
            var maq_num = $("#maq_num").val();
            var maq_modelo = $("#maq_modelo").val();
            var maq_marca = $("#maq_marca").val();
            var maq_estado = $("#maq_estado option:selected").val();
            var maq_ingreso = $("#maq_ingreso").val();
            var maq_mantencion = $("#maq_mantencion").val();

            // se formatea la fecha para mysql
            var f1 = maq_ingreso.split("/");
            maq_ingreso = f1[2]+"-"+f1[1]+"-"+f1[0];

            if (maq_mantencion != ""){
            	var f2 = maq_mantencion.split("/");
            	maq_mantencion = f2[2]+"-"+f2[1]+"-"+f2[0];
            }

            // se hace la peticion ajax para agregar
            $.ajax({        
                type: "POST",
                url: url_global+"Maquina/agregar",
                data: {
                    maq_tipo: maq_tipo,
                    maq_num: maq_num,
                    maq_modelo: maq_modelo,
                    maq_marca: maq_marca,
                    maq_estado: maq_estado,
                    maq_ingreso: maq_ingreso,
                    maq_mantencion: maq_mantencion,
                    llave: true
                }
            }).done(function(response){

                alert(response);
                // se obtiene el mensaje
                var mensaje = JSON.parse(response);

                // si no hay errores
                if (mensaje[0] == 1) {

                    // se muestra el mensaje de exito
                    $("#vista_footer p").text(mensaje[1]);

                    // se obtienen los valores para actualizar la tabla
                    maq_tipo = $("#maq_tipo option:selected").text();
                    maq_estado = $("#maq_estado option:selected").text();
                    maq_ingreso = $("#maq_ingreso").val();
                    maq_mantencion = $("#maq_mantencion").val();

                    // se agregan los datos a la tabla
                    tabla.row.add([
                        maq_tipo,
                        maq_num,
                        maq_marca,
                        maq_modelo,
                        maq_estado,
                        maq_ingreso,
                        maq_mantencion,
                        0
                    ]).draw(false);

                    // se aumenta la cantidad del tipo de maquina
                    $("#maq_num").val(parseInt(maq_num)+1);
                    for (var i=0; i<tipos_post.length; i++) {
                        if (tipos_post[i][0] == $("#maq_tipo option:selected").val()) {
                            tipos_post[i][2] = parseInt(tipos_post[i][2])+1;
                        }
                    }

                }
                // de lo contrario, se muestra el mensaje de error
                else {
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
    // ---------------------------------------------------------------------------------------------
    // confirmar modificar
    else if (estado == 2)
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
            mensaje_confirmar(
                "Confirmar Modificación",
                "Esta segura de modificar la máquina "+fila_select[0]+" "+fila_select[1]+" ?",
            function(){
                // se obtienen los valores de los campos
                var maq_tipo = $("#maq_tipo option:selected").val();
                var maq_num = $("#maq_num").val();
                var maq_modelo = $("#maq_modelo").val();
                var maq_marca = $("#maq_marca").val();
                var maq_estado = $("#maq_estado option:selected").val();
                var maq_ingreso = $("#maq_ingreso").val();
                var maq_mantencion = $("#maq_mantencion").val();

                fila_select[5] = maq_ingreso;
                fila_select[6] = maq_mantencion;

                // se formatea la fecha para mysql
                var f1 = maq_ingreso.split("/");
                maq_ingreso = f1[2]+"-"+f1[1]+"-"+f1[0];

                if (maq_mantencion != ""){
                    var f2 = maq_mantencion.split("/");
                    maq_mantencion = f2[2]+"-"+f2[1]+"-"+f2[0];
                }
                
                // se actualizan los datos en la tabla
                fila_select[2] = maq_marca;
                fila_select[3] = maq_modelo;
                fila_select[4] = $("#maq_estado option:selected").text();

                // se hace la peticion ajax para modificar
                $.ajax({        
                    type: "POST",
                    url: url_global+"Maquina/modificar",
                    data: {
                        maq_tipo: maq_tipo,
                        maq_num: maq_num,
                        maq_modelo: maq_modelo,
                        maq_marca: maq_marca,
                        maq_estado: maq_estado,
                        maq_ingreso: maq_ingreso,
                        maq_mantencion: maq_mantencion,
                        llave: true         
                    }
                }).done(function(response){
                    var mensaje = JSON.parse(response);
                    if (mensaje[0] == 1) {
                        $("#vista_footer p").text(mensaje[1]);
                        tabla.row(fila_index).data(fila_select).draw();
                    }
                    else mensaje_error(mensaje[1]);
                });
            },function(){
                $("#vista_footer p").text("Se canceló la operación de modificación");
            })
        }
        else
        {
            // se enfoca el primer input que no se ha completado
            $(primero).focus();

            // se muestra el mensaje
            $("#vista_footer p").text("Recuerde completar todos los campos requeridos (*)");
        } 
    }
    // ---------------------------------------------------------------------------------------------
    // generar informe de consulta
    else if (estado == 3)
    {
        // tabla que se enviara a pdfmake para generar el documento
        var mi_tabla = [];

        // arreglo de anchos de las columnas
        var mi_anchos = ["*","auto"];

        // se agregan las cabeceras a la tabla
        var mi_cabecera = [
            {text:"Tipo de Máquina",bold:true,fillColor:"#DBDBDB"},
            {text:"Número",bold:true,fillColor:"#DBDBDB"}
        ];

        // se agrega cabecera dependiendo si esta visible
        for (var i in tabla_headers)
        {
            if (i > 1)
            {
                var mi_head = tabla_headers[i];
                if (tabla.column(i).visible() == true) {
                    mi_cabecera.push({text:mi_head,bold:true,fillColor:"#DBDBDB"});
                    mi_anchos.push("auto");
                }
            }
        }

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
                            text: "Listado de Máquinas",
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

            var mi_orientacion = $("#orientacion option:selected").val();
            if (mi_orientacion == 1) {
                docDefinition.pageOrientation = "landscape";
            }

            // open the PDF in a new window
            pdfMake.createPdf(docDefinition).open();
        }
        else 
        {
            $("#vista_footer p").text("No hay maquinas seleccionadas para generar el informe");
        }
    }
    // ---------------------------------------------------------------------------------------------
    // confirmar eliminar
    else if (estado == 4)
    {
        // se pregunta si realmente se quiere eliminar
        mensaje_confirmar(
            "Confirmar eliminación",
            "Esta seguro que desea eliminar la máquina "+fila_select[0]+" "+fila_select[1]+" ?",
        function(){

            // se obtienen los valores de los campos
            var tipo = "CO";
            if (fila_select[0] == "Overlock") tipo = "OV";
            if (fila_select[0] == "Máquina de Coser Doméstica") tipo = "MC";
            var num = fila_select[1];

            // se hace la peticion ajax para eliminar
            $.ajax({        
                type: "POST",
                url: url_global+"Maquina/eliminar",
                data: {
                    maq_tipo: tipo,
                    maq_num: num,
                    llave: true           
                }
            }).done(function(response){
                var mensaje = JSON.parse(response);

                if (mensaje[0] == 1) {
                    $("#vista_footer p").text(mensaje[1]);
                    tabla.row(fila_index).remove().draw();
                    for (var i=0; i<tipos_post.length; i++) {
                        if (tipos_post[i][0] == tipo) {
                            tipos_post[i][2] = parseInt(tipos_post[i][2])-1;
                            $("#maq_num").val(parseInt(tipos_post[i][2]));
                        }
                    }
                } else {
                    mensaje_error(mensaje[1]);
                }
            });
        },
        function(){
            $("#vista_footer p").text("se canceló la operación de eliminación");
        });
    }
});

// se actualiza la cantidad de maquinas dependiendo de que tipo de maquina se selecciona
$('#maq_tipo').on( 'change', function(){
    var v = $("#maq_tipo option:selected").val();
    for (var i=0; i<tipos_post.length; i++){
        if (tipos_post[i][0] == v) {
            $("#maq_num").val(parseInt(tipos_post[i][2])+1);
        }
    }
});

// se definen los daterangepicker ----------------------------------------------------------------
$(function() {
    $('#maq_ingreso').daterangepicker({
        singleDatePicker: true,
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
    $(".range_inputs").remove();
    $('#maq_mantencion').daterangepicker({
        autoUpdateInput: false,
        singleDatePicker: true,
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
    $('#maq_mantencion').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY'));
    });
});