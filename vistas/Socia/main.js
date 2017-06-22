// variables de entorno-----------------------------------------------------------------------------
// inputs requeridos solo en los estados 1,2
var required = [
    "socia_rut",
    "socia_fono",
    "socia_direccion",
    "socia_nombre",
    "socia_paterno",
    "socia_materno"
];

// si es tesorera o socia normal, el estado es solo de consulta
// 1: agregar
// 2: modificar
// 3: consultar
// 4: eliminar
var estado = 1;
if (login_pass >= 2) estado = 3;

// obtener alto para la tabla
function alto_tabla() 
{
    // se obtiene el alto total y del formulario
    var tot_alto = $("#vista_contenido").height();
    var frm_alto = $(".formulario").height();

    // se retorna el alto dependiendo del estado
    if (estado == 4) return tot_alto-100;
    else if (estado == 3) return tot_alto-160;
    else return Math.max(tot_alto-frm_alto-100,250);
}

// se obtiene el alto inicial del formulario
var formulario_h = $("#vista_contenido .formulario").height();

// para ver el contenido de un dropdown fuera del contenedor
$('.dropdown-toggle').click(function (){
    dropDownFixPosition($(this),$(this).parent().children(".dropdown-menu"));
});
function dropDownFixPosition(button,dropdown){
    var dropDownTop = button.offset().top + button.outerHeight();
    dropdown.css('top',dropDownTop+"px");
    dropdown.css('left',button.offset().left+"px");
}

// cambiar la visibilidad de las columnas en la consulta
$(".dropdown .dropdown-menu li a").click(function(e){
    e.stopPropagation();
    var mi_span = $(this).find("span");
    var mi_opcion = $(this).find(".vis-opcion").text();
    var mi_columna = $(this).attr("columna");

    // se oculta la columna
    if (mi_span.hasClass("glyphicon-eye-open")) {

        mi_span.removeClass("glyphicon-eye-open");
        mi_span.addClass("glyphicon-eye-close");

        var column = tabla.column(mi_columna);
        column.visible(false);

    // se muestra la columna
    } else {

        mi_span.removeClass("glyphicon-eye-close");
        mi_span.addClass("glyphicon-eye-open");

        var column = tabla.column(mi_columna);
        column.visible(true);
    }
});

// funcion para validar el email
function validar_email(email) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}

// inicializar la tabla
var tabla;
var fila_select;
var fila_index = -1;
$(document).ready(function(){

    // se agregan inputs de busqueda
    $('#tabla tfoot th').each(function(){
        var title = $(this).text();
        var mi_html = '<input type="text" style="width:inherit;" placeholder="Buscar por '+title+'" />';
        $(this).html(mi_html);
    });
   
    // se carga la forma como datatable
    tabla = $('#tabla').DataTable({
        "scrollY": alto_tabla(),
        "scrollX": true,
        "paging": false,
        "searching": true,
        "aaSorting": [],
        "info": false,
        "language": {
            "emptyTable": "No hay datos disponibles",
            "zeroRecords" : "No hay coincidencias"
        }
    });

    // si el permiso es admin, presidenta o secretaria, la ultima columna se debe ocultar
    if (login_pass < 2) {
        tabla.column(9).visible(false);
    }
    
    // se ejecuta para mostrar el contenido de la tabla
    $(function(){
        $("#tabla-content").removeAttr("hidden");
        $("#tabla_filter").remove();
        tabla.draw();
    });
    
    // para seleccionar filas
    if (login_pass < 2) {
        $('#tabla tbody').on( 'click', 'tr', function () {

            // solo en modificar o eliminar
            if (estado == 2 || estado == 4) {
                
                $("#tabla tbody tr").removeClass('selected');
                $(this).toggleClass('selected');
                fila_select = tabla.row(this).data();
                fila_index = tabla.row(this).index();

                // se actualizan los input si se modifica
                if (estado == 2)
                {
                    $("#socia_rut").val(fila_select[0]);
                    $("#socia_nombre").val(fila_select[1]);
                    $("#socia_paterno").val(fila_select[2]);
                    $("#socia_materno").val(fila_select[3]);
                    $("#socia_fono").val(fila_select[4]);
                    $("#socia_direccion").val(fila_select[5]);
                    $("#socia_email").val(fila_select[6]);

                    var valor = -1;
                    if (fila_select[7] == "Presidenta") valor = 0;
                    if (fila_select[7] == "Secretaria") valor = 1;
                    if (fila_select[7] == "Tesorera") valor = 2;
                    if (fila_select[7] == "Socia") valor = 3;
                    $("#socia_permiso").val(valor);

                    $("#socia_ingreso").data('daterangepicker').setStartDate(fila_select[8]);
                    $("#socia_ingreso").data('daterangepicker').setEndDate(fila_select[8]);

                    $(".formulario input").prop("disabled",false);
                    $(".formulario select").prop("disabled",false);
                    $("#socia_permiso").prop("disabled",true);
                    $("#socia_rut").prop("disabled",true);

                    $("#vista_footer p").text("La socia seleccionada esta lista para modificar");
                    $("#MantConfirmar").show();
                }
                if (estado == 4) {
                    $("#vista_footer p").text("La socia seleccionada esta lista para eliminar");
                    $("#MantConfirmar").show();
                }
            }
        });
    }

    // para activar la busqueda por cada input
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

// para volver a ingresar una socia eliminada, se cargan todos sus datos
// si se selecciona en la datalist
$("#socia_rut").on('input', function () {
    var val = this.value;
    if($('#socia_rut_list').find('option').filter(function(){
        return this.value.toUpperCase() === val.toUpperCase();        
    }).length) {
        // recorremos el arreglo de socias eliminadas
        for (var i in eliminadas) {
            var socia = eliminadas[i];
            if (this.value == socia[0]) {
                $("#socia_nombre").val(socia[1]);
                $("#socia_paterno").val(socia[2]);
                $("#socia_materno").val(socia[3]);
                $("#socia_fono").val(socia[4]);
                $("#socia_direccion").val(socia[5]);
                $("#socia_email").val(socia[6]);

                var valor = -1;
                if (socia[7] == "Presidenta") valor = 0;
                if (socia[7] == "Secretaria") valor = 1;
                if (socia[7] == "Tesorera") valor = 2;
                if (socia[7] == "Socia") valor = 3;
                $("#socia_permiso").val(valor);

                $("#socia_ingreso").data('daterangepicker').setStartDate(socia[8]);
                $("#socia_ingreso").data('daterangepicker').setEndDate(socia[8]);
            }
        }
    }
});

// cuando se redimensiona la ventana del navegador--------------------------------------------------
$(window).resize(function(){
    $("div.dataTables_scrollBody").height(alto_tabla());
});

if (login_pass < 2) 
{
    // cambio de estados al seleccionar opciones--------------------------------------------------------
    // cuando se presiona agregar
    $("#MantAgregar").click(function(){
        if (estado != 1) {
            $("#vista_contenido .formulario .form-group").removeClass("has-error");
            $("#vista_contenido .formulario input").css({"background-color":""});
            $("#vista_header p").text("Socia : Agregar");
            $("#vista_footer p").html("Complete todos los campos requeridos <b>(*)</b>");
            $(".formulario input").prop("disabled",false);
            $(".formulario select").prop("disabled",false);
            $("#vista_contenido .formulario").show();
            $("#vista_contenido .formulario").prop("disabled",false);
            $("#vista_contenido .formulario").height(formulario_h);
            $(".fila-general").show();
            $(".fila-consulta").hide();
            $("#tabla tbody tr").removeClass('selected');
            $("#MantConfirmar").text("Confirmar");
            $("#MantConfirmar").show();
            $(".formulario input:not(#socia_ingreso)").val("");
            estado = 1;
            $("#socia_nombre").focus();
            $("div.dataTables_scrollBody").height(alto_tabla());

            // se cargan nuevamente los datos si socia_estado es eliminada
            var mi_estado = $("#socia_estado option:selected").val();
            if (mi_estado == 3) {

                // se hace una peticion ajax para cargar la tabla
                $.ajax({
                    type: "POST",
                    url: url_global+"Socia/listar",
                    data: {
                        permiso: login_pass,
                        estado: 1,
                        llave: true
                    }
                }).done(function(response) {

                    // borramos todos los elementos de la tabla
                    tabla.clear();
                    
                    // se cargan los datos a la tabla
                    var socias = JSON.parse(response);
                    
                    // se recorre el arreglo de socias sin considerar la primera fila
                    for (var i in socias) {
                        var socia = socias[i];
                        if (i > 0) tabla.row.add(socia);
                    }

                    // se actualiza la tabla
                    tabla.draw();

                    // cambiamos el estado
                    $("#socia_estado").val(1);

                    // ocultamos la ultima columna
                    tabla.column(9).visible(false);
                    $("li a[columna=9]").parent().hide();
                });
            }

            // se hacen todas las columnas visibles
            $(".dropdown-menu li a span").removeClass('glyphicon-eye-close');
            $(".dropdown-menu li a span").addClass('glyphicon-eye-open');
            tabla.columns().visible(true);
            tabla.column(9).visible(false);

            $("#socia_ingreso").data('daterangepicker').setStartDate(moment(new Date()).format('DD/MM/YYYY'));
        }
    });
    // cuando se presiona modificar
    $("#MantModificar").click(function(){
        if (estado != 2) {
            $("#vista_contenido .formulario .form-group").removeClass("has-error");
            $("#vista_contenido .formulario input").css({"background-color":""});
            $("#vista_header p").text("Socia : Modificar");
            $("#vista_footer p").text("Seleccione un elemento en la tabla");
            $(".formulario input").prop("disabled",true);
            $(".formulario  select").prop("disabled",true);
            $("#socia_rut").prop("disabled",true);
            $("#vista_contenido .formulario").show();
            $("#vista_contenido .formulario").prop("disabled",true);
            $("#vista_contenido .formulario").height(formulario_h);
            $(".fila-general").show();
            $(".fila-consulta").hide();
            $("#tabla tbody tr").removeClass('selected');
            $("#MantConfirmar").text("Confirmar");
            $("#MantConfirmar").hide();
            $(".formulario input:not(#socia_ingreso)").val("");
            estado = 2;
            $("div.dataTables_scrollBody").height(alto_tabla());

            // se cargan nuevamente los datos si socia_estado es eliminada
            var mi_estado = $("#socia_estado option:selected").val();
            if (mi_estado == 3) {

                // se hace una peticion ajax para cargar la tabla
                $.ajax({
                    type: "POST",
                    url: url_global+"Socia/listar",
                    data: {
                        permiso: login_pass,
                        estado: 1,
                        llave: true
                    }
                }).done(function(response) {

                    // borramos todos los elementos de la tabla
                    tabla.clear();
                    
                    // se cargan los datos a la tabla
                    var socias = JSON.parse(response);
                    
                    // se recorre el arreglo de socias sin considerar la primera fila
                    for (var i in socias) {
                        var socia = socias[i];
                        if (i > 0) tabla.row.add(socia);
                    }

                    // se actualiza la tabla
                    tabla.draw();

                    // cambiamos el estado
                    $("#socia_estado").val(1);

                    // ocultamos la ultima columna
                    tabla.column(9).visible(false);
                    $("li a[columna=9]").parent().hide();
                });
            }

            // se hacen todas las columnas visibles
            $(".dropdown-menu li a span").removeClass('glyphicon-eye-close');
            $(".dropdown-menu li a span").addClass('glyphicon-eye-open');
            tabla.columns().visible(true);
            tabla.column(9).visible(false);

            $("#socia_ingreso").val("");
        }
    });
    // cuando se presiona consultar
    $("#MantConsultar").click(function(){
        if (estado != 3) {
            $("#vista_header p").text("Socia : Consultar");
            $("#vista_footer p").text("Llene los campos para realizar una busqueda");
            $("#vista_contenido .formulario").show();
            $("#vista_contenido .formulario").prop("disabled",false);
            $("#vista_contenido .formulario").height(60);
            $(".formulario select").prop("disabled",false);
            $(".fila-general").hide();
            $(".fila-consulta").show();
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
            $("#vista_header p").text("Socia : Eliminar");
            $("#vista_footer p").text("Seleccione un elemento en la tabla");
            $("#vista_contenido .formulario").hide();
            $("#tabla tbody tr").removeClass('selected');
            $("#MantConfirmar").text("Confirmar");
            $("#MantConfirmar").hide();
            estado = 4;
            $("div.dataTables_scrollBody").height(alto_tabla());

            // se cargan nuevamente los datos si socia_estado es eliminada
            var mi_estado = $("#socia_estado option:selected").val();
            if (mi_estado == 3) {

                // se hace una peticion ajax para cargar la tabla
                $.ajax({
                    type: "POST",
                    url: url_global+"Socia/listar",
                    data: {
                        permiso: login_pass,
                        estado: 1,
                        llave: true
                    }
                }).done(function(response) {

                    // borramos todos los elementos de la tabla
                    tabla.clear();
                    
                    // se cargan los datos a la tabla
                    var socias = JSON.parse(response);
                    
                    // se recorre el arreglo de socias sin considerar la primera fila
                    for (var i in socias) {
                        var socia = socias[i];
                        if (i > 0) tabla.row.add(socia);
                    }

                    // se actualiza la tabla
                    tabla.draw();

                    // cambiamos el estado
                    $("#socia_estado").val(1);

                    // ocultamos la ultima columna
                    tabla.column(9).visible(false);
                    $("li a[columna=9]").parent().hide();
                });
            }

            // se hacen todas las columnas visibles
            $(".dropdown-menu li a span").removeClass('glyphicon-eye-close');
            $(".dropdown-menu li a span").addClass('glyphicon-eye-open');
            tabla.columns().visible(true);
            tabla.column(9).visible(false);
        }
    });

    // quitar el borde de alerta------------------------------------------------------------------------
    $("#vista_opciones button").click(function(){
        $("#vista_contenido .formulario .form-group").removeClass("has-error");
    });
}

// si se selecciona un estado de socia diferente----------------------------------------------------
if (login_pass < 3) 
{
    $("#socia_estado").on('change',function(){

        // se obtiene el estado
        var mi_estado = $(this).val();

        // se hace una peticion ajax para cargar la tabla
        $.ajax({
            type: "POST",
            url: url_global+"Socia/listar",
            data: {
                permiso: login_pass,
                estado: mi_estado,
                llave: true
            }
        }).done(function(response) {

            // borramos todos los elementos de la tabla
            tabla.clear();
            
            // se cargan los datos a la tabla
            var socias = JSON.parse(response);
            
            // se recorre el arreglo de socias sin considerar la primera fila
            for (var i in socias) {
                var socia = socias[i];
                if (i > 0) tabla.row.add(socia);
            }

            // se actualiza la tabla
            tabla.draw();

            // si el estado es eliminada, se muestra la fecha de eliminacion
            if (login_pass < 2)
            {
                if (mi_estado == 1) {
                    tabla.column(9).visible(false);
                    $("li a[columna=9]").parent().hide();
                } else {
                    tabla.column(9).visible(true);
                    $("li a[columna=9]").parent().show();
                    $("li a[columna=9] span").removeClass('glyphicon-eye-close');
                    $("li a[columna=9] span").addClass('glyphicon-eye-open');
                }
            }
        });
    });
}

// confirmar operaciones----------------------------------------------------------------------------
$("#MantConfirmar").click(function(e){
    
    if (login_pass < 2) 
    {
        $("#vista_contenido .formulario .form-group").removeClass("has-error");
        $("#vista_contenido .formulario input").css({"background-color":""});
       
        // confirmar agregar
        if (estado == 1)
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
                // se valida el rut
                var rut = $("#socia_rut").val();

                // vemos si el rut pertenece a una socia antigua
                index_eliminada = -1;
                for (var i in eliminadas) {
                    var socia = eliminadas[i];
                    if (socia[0] == rut) {
                        index_eliminada = i;
                        break;
                    }
                }

                // validacion del rut
                var rut_val = rut_validar(rut);

                // se valida el rut
                if (rut_val === true)
                {
                    var email = $("#socia_email").val();
                    if (email == "" || (email != "" && validar_email(email)))
                    {
                        // se obtienen los valores de los campos
                        var nombre = $("#socia_nombre").val();
                        var paterno = $("#socia_paterno").val();
                        var materno = $("#socia_materno").val();
                        var fono = $("#socia_fono").val();
                        var direccion = $("#socia_direccion").val();
                        
                        var ingreso = $("#socia_ingreso").val();
                        var permiso = $("#socia_permiso option:selected").val();
                        
                        // se formatea la fecha para mysql
                        var fecha = ingreso.split("/");
                        ingreso = fecha[2]+"-"+fecha[1]+"-"+fecha[0];

                        // se hace la peticion ajax para agregar
                        $.ajax({        
                            type: "POST",
                            url: url_global+"Socia/agregar",
                            data: {
                                socia_rut: rut,
                                socia_nombre: nombre,
                                socia_paterno: paterno,
                                socia_materno:materno,
                                socia_fono: fono,
                                socia_direccion:direccion,
                                socia_email:email,
                                socia_ingreso:ingreso,
                                socia_permiso:permiso,
                                llave: true                  
                            }
                        }).done(function(response){
                            var mensaje = JSON.parse(response);
                            if (mensaje[0] == 1){
                                $("#vista_footer p").text(mensaje[1]);
                                var permiso_text = $("#socia_permiso option:selected").text();
                                var estado_text = $("#socia_estado option:selected").text();
                                ingreso = $("#socia_ingreso").val();
                                tabla.row.add([
                                    rut,
                                    nombre,
                                    paterno,
                                    materno,
                                    fono,
                                    direccion,
                                    email,
                                    permiso_text,
                                    ingreso,
                                    ""
                                ]).draw(false);
                                $(".formulario input").val("");
                                $("#socia_nombre").focus();

                                // si es una socia antigua
                                if (index_eliminada != -1) {

                                    // borramos del datalist
                                    $("#socia_rut_list option").each(function(i,v){
                                        var mi_rut = $(this).val();
                                        if (mi_rut == eliminadas[index_eliminada][0]) {
                                            $(this).remove();
                                        }
                                    });

                                    // borramos del arreglo de eliminadas
                                    eliminadas.splice(index_eliminada, 1);
                                }
                            }
                            else{
                                mensaje_error(mensaje[1]);
                            }
                        });
                    }
                    else
                    {
                        // se enfoca el primer input que no se ha completado
                        $("#socia_email").parents(".form-group").addClass("has-error");
                        $("#socia_email").css({"background-color":"#FDCDCD"});
                        $("#socia_email").focus();

                        // se muestra el mensaje
                        $("#vista_footer p").text("Email con formato inválido");
                    }
                }
                else
                {
                    // se enfoca el primer input que no se ha completado
                    $("#socia_rut").parents(".form-group").addClass("has-error");
                    $("#socia_rut").css({"background-color":"#FDCDCD"});
                    $("#socia_rut").focus();

                    // se muestra el mensaje
                    mensaje_error(rut_val);
                }
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
        if (estado == 2)
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
                var email = $("#socia_email").val();
                if (email == "" || (email != "" && validar_email(email)))
                {
                    mensaje_confirmar(
                        "Confirmar modificación",
                        "Esta segura que desea modificar a la socia con el rut "+$("#socia_rut").val(),
                    function(){
                        // se actualizan los datos en la tabla
                        fila_select[1] = $("#socia_nombre").val();
                        fila_select[2] = $("#socia_paterno").val();
                        fila_select[3] = $("#socia_materno").val();
                        fila_select[4] = $("#socia_fono").val();
                        fila_select[5] = $("#socia_direccion").val();
                        fila_select[6] = $("#socia_email").val();
                        fila_select[8] = $("#socia_ingreso").val();
                        fila_select[7] = $("#socia_permiso option:selected").text();
                        // fila_select[7] = $("#socia_estado option:selected").text();
                        tabla.row(fila_index).data(fila_select).draw();

                        // se obtienen los valores de los campos
                        var rut = $("#socia_rut").val();
                        var nombre = $("#socia_nombre").val();
                        var paterno = $("#socia_paterno").val();
                        var materno = $("#socia_materno").val();
                        var fono = $("#socia_fono").val();
                        var direccion = $("#socia_direccion").val();
                        var email = $("#socia_email").val();
                        var ingreso = $("#socia_ingreso").val();
                        var permiso = $("#socia_permiso option:selected").val();
                        
                        // se formatea la fecha para mysql
                        var fechaaarreglo = ingreso.split("/");
                        ingreso = fechaaarreglo[2]+"-"+fechaaarreglo[1]+"-"+fechaaarreglo[0];

                        // se hace la peticion ajax para modificar
                        $.ajax({        
                            type: "POST",
                            url: url_global+"Socia/modificar",
                            data: {
                                socia_rut: rut,
                                socia_nombre: nombre,
                                socia_paterno: paterno,
                                socia_materno:materno,
                                socia_fono: fono,
                                socia_direccion:direccion,
                                socia_email:email,
                                socia_ingreso:ingreso,
                                socia_permiso:permiso,
                                llave: true               
                            }
                        }).done(function(response){
                            var mensaje = JSON.parse(response);
                            if (mensaje[0] == 1) $("#vista_footer p").text(mensaje[1]);
                            else mensaje_error(mensaje[1]);
                            if (login_rut == rut) {
                                $("#principal_bienvenido a span").text(nombre);
                            }
                        });
                    },function(){
                        // se muestra el mensaje
                        $("#vista_footer p").text("Se canceló la operación de modificación");
                    });   
                }
                else
                {
                    // se enfoca el primer input que no se ha completado
                    $("#socia_email").parents(".form-group").addClass("has-error");
                    $("#socia_email").css({"background-color":"#FDCDCD"});
                    $("#socia_email").focus();

                    // se muestra el mensaje
                    $("#vista_footer p").text("Email con formato inválido");
                }
            }
            else
            {
                // se enfoca el primer input que no se ha completado
                $(primero).focus();

                // se muestra el mensaje
                $("#vista_footer p").text("Recuerde completar todos los campos requeridos (*)");
            }
        }
        // confirmar eliminar
        if (estado == 4)
        {
            // se pregunta si realmente se quiere eliminar
            mensaje_confirmar("Confirmar eliminación","Esta seguro que desea eliminar a "+fila_select[1]+" ?",
            function(){
                // se hace la peticion ajax para eliminar
                $.ajax({        
                    type: "POST",
                    url: url_global+"Socia/eliminar",
                    data: {
                        socia_rut: fila_select[0],
                        usuario_rut: login_rut,
                        llave: true                   
                    }
                }).done(function(response){

                    // obtenemos el arreglo de mensaje
                    var mensaje = JSON.parse(response);

                    if (mensaje[0] == 1){
                        $("#vista_footer p").text(mensaje[1]);
                        tabla.row(fila_index).remove().draw();
                    }
                    else if (mensaje[0] == 2)
                    {
                        var mi_html = "La socia no puede ser eliminada ";
                        mi_html += "de la base de datos por las siguientes razones: <br/><br/>";
                        mi_html += "<ul>";
                        for (var i in mensaje[1])
                        {
                            mi_html += "<li>";
                            mi_html += mensaje[1][i];
                            mi_html += "</li>";
                        }
                        mi_html += "</ul><br/>";
                        mi_html += "Desea cambiar el estado a eliminada?";
                        mensaje_confirmar("Confirmar eliminación",mi_html,
                        function(){
                            $.ajax({        
                                type: "POST",
                                url: url_global+"Socia/eliminar_logicamente",
                                data: {
                                    socia_rut: fila_select[0],
                                    usuario_rut: login_rut,
                                    fecha_elim: moment(new Date()).format('YYYY-MM-DD'),
                                    llave: true                  
                                }
                            }).done(function(response2){
                                var msj2 = JSON.parse(response2);
                                if (msj2[0] == 1){
                                    $("#vista_footer p").text(msj2[1]);
                                    eliminadas.push(fila_select);
                                    $("#socia_rut_list").append("<option value='"+fila_select[0]+"'></option>");
                                    tabla.row(fila_index).remove().draw();
                                } else {
                                    $("#vista_footer p").text(msj2[1]);
                                }
                            });
                        },function(){
                            $("#vista_footer p").text("Se canceló la operación de eliminación");
                        });
                    }
                    else {
                        mensaje_error(mensaje[1]);
                    }
                });
            },function(){
                $("#vista_footer p").text("Se canceló la operación de eliminación");
            });
        }
    }
    
    // generar informe de consulta
    if (estado == 3)
    {
        // tabla que se enviara a pdfmake para generar el documento
        var mi_tabla = [];

        // arreglo de anchos de las columnas
        var mi_anchos = ["auto","*"];

        // se agregan las cabeceras a la tabla
        var mi_cabecera = [
            {text:"Rut",bold:true,fillColor:"#DBDBDB"},
            {text:"Nombre Completo",bold:true,fillColor:"#DBDBDB"}
        ];

        // se agrega cabecera dependiendo si esta visible
        for (var i in socias[0])
        {
            if (i > 3)
            {
                var mi_head = socias[0][i];
                if (tabla.column(i).visible() == true) {
                    mi_cabecera.push({text:mi_head,bold:true,fillColor:"#DBDBDB"});
                    mi_anchos.push("auto");
                }
            }
        }

        // para obtener la columna de los valores acumulados y deuda
        // y asi alinearlos a la derecha
        var vai = -1, vad = -1;
        if (login_pass == 2)
        {
            // se recorren las cabeceras
            for (var i in mi_cabecera)
            {
                var mi_elemento = mi_cabecera[i];
                if (mi_elemento.text == "Acumulado ($)") vai = parseInt(i)+2;
                if (mi_elemento.text == "Deuda ($)") vad = parseInt(i)+2;
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

                    // para unir en una columna todo el nombre
                    var mi_nombre = "";
                    var mi_apellido = "";

                    // se recorren los elementos de la fila
                    $(this).find('td').each(function(ii,vv){

                        var mi_elemento = $(this).text();

                        // se guarda el nombre completo
                        if (ii > 0 && ii < 4) {
                            if (ii == 1) mi_nombre += mi_elemento;
                            else mi_apellido += mi_elemento+" ";
                            if (ii == 3) {
                                mi_fila.push("");
                            }
                        }
                        else {
                            if (login_pass == 2 && (ii == vai || ii == vad)) {
                                mi_fila.push({text:mi_elemento,alignment:"right"});
                            } else {
                                mi_fila.push(mi_elemento);
                            }
                            
                        }

                    });

                    // se coloca el nombre
                    mi_fila[1] = mi_apellido+mi_nombre;
                    mi_tabla.push(mi_fila);
                }
            }
        });

        if (mi_tabla.length > 1)
        {
            // cambiar el titulo dependiendo de estado_socia
            var mi_estado = $("#socia_estado option:selected").val();
            var mi_titulo = "Listado actual de socias activas";
            if (mi_estado == 3) {
                mi_titulo = "Listado de socias eliminadas";
            }

            // definicion del documento
            var docDefinition = { 
                header: {
                    margin: 20,
                    columns: [
                        {
                            text: mi_titulo,
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
            $("#vista_footer p").text("No hay socias seleccionadas para generar el informe");
        }
    }
});

if (login_pass < 2) 
{
    $('#socia_rut').keypress(function(e){

        // se obtiene el contenido del input
        contenido = $(this).val();

        // se obtiene el ultimo caracter del contenido
        ultimo = contenido.charAt(contenido.length-1);

        // si alguno se cumple, ignora la nueva entrada
        if ((e.key < '0' || e.key > '9') 
        && ((e.key != "K" && e.key != "k") || ultimo != "-") 
        && (e.key != "-" || cuenta(contenido,"-") != 0 || contenido.length <= 6)
        && e.charCode != 0)
            return false;
    });

    //date picker
    $(function() {
        $('#socia_ingreso').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            timePicker:false,
            maxDate: new Date(),
            minDate: new Date("2000-01-01 00:00:00"),
            locale: {
                firstDay: 1,
                format: 'DD/MM/YYYY',
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
    });
}