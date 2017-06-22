// funcion para cargar las maquinas disponibles en el dropdown
function cargar_tipo_maquinas() {

    // se obtiene la fecha seleccionada y se da formato
    var val_fecha = $("#fecha").val();
    var f1 = val_fecha.split("/");
    val_fecha = f1[2]+"-"+f1[1]+"-"+f1[0];

    // se hace la peticion
    $.ajax({
        type: "POST",
        url: url_global+"Maquina/consultar_maquinas",
        data: {
            fecha_solicitud: val_fecha,
            llave: true
        }
    }).done(function(response){

        // limpiamos el combobox
        $("#tipo-group .dropdown-menu").empty();
        var cantidad_total = 0;

        // llenamos con los nuevos datos
        var tipos = JSON.parse(response);
        for (var i in tipos) 
        {
            var mi_tipo = tipos[i][0];
            var mi_descripcion = tipos[i][1]
            var mi_cantidad  = tipos[i][2];
            var mi_html = "<li><a style='cursor: pointer;' columna='"+mi_tipo+"'";
            if (mi_cantidad == "0") mi_html += " disabled";
            mi_html += ">";
            mi_html += "<div class='row'>";
            mi_html += "<div class='col-md-10 tipo-opcion'>"+mi_descripcion+"</div>";
            mi_html += "<div class='col-md-2'>";
            mi_html += "<span class='tag' style='margin-left: -6px;text-align: center;'>"+mi_cantidad+"</span>";
            mi_html += "</div></div></a></li>";
            $("#tipo-group .dropdown-menu").append(mi_html);

            if (estado == 1) {
                $("#vista_footer p").text("Seleccione el tipo de máquina y confirme su solicitud");
            }
            
            cantidad_total += parseInt(mi_cantidad);
        }

        // mostramos el total de maquinas disponibles
        $("#tipo-group button .tag").text(cantidad_total);

        // mostramos el dropdown
        $("#tipo-group").show();

        // se muestra el mensaje de confirmacion
        $("#Confirmar").show();

        // se agrega el evento a los links del dropdown
        $("#tipo-group .dropdown-menu li a").on("click",function(){

            // se obtiene la opcion y el codigo de la maquina
            var mi_opcion = $(this).find(".tipo-opcion").text();
            var mi_columna = $(this).attr("columna");

            // se guarda la opcion y el codigo de la maquina
            $("#tipo-select").text(mi_opcion);
            $("#tipo-columna").text(mi_columna);
            $("#vista_footer p").text("Se seleccionó "+mi_opcion+", confirme su solicitud");
        });

    });
}

// para ver el contenido de un dropdown fuera del contenedor
$('#tipo-group .dropdown-toggle').click(function (){
    dropDownFixPosition($(this),$(this).parent().children(".dropdown-menu"));
});
function dropDownFixPosition(button,dropdown){
    var dropDownTop = button.offset().top + button.outerHeight();
    dropdown.css('top',dropDownTop+"px");
    dropdown.css('left',button.offset().left+"px");
}

// se carga daterangepicker en #fecha
$(function() {
    $('#fecha').daterangepicker({
        autoUpdateInput: false,
        singleDatePicker: true,
        showDropdowns: true,
        minDate: new Date(),
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
    $('#fecha').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY'));
        cargar_tipo_maquinas();
    });
    $("#fecha").focusout(function(){
        if ($(this).val() == "") {
            $(this).val(moment(new Date()).format('DD/MM/YYYY'));
            cargar_tipo_maquinas();
        }
    });
    $(".range_inputs").remove();
});

// 1: agregar
// 2: modificar
// 3: eliminar
var estado = 1;

// obtener alto para la tabla
function alto_tabla() 
{
    if (estado == 3) return $("#vista_contenido").height()-50;
    else return Math.max($("#vista_contenido").height()-$(".formulario").height()-50,250);
}

var tabla;
var fila_select;
var fila_index = -1;
$(document).ready(function(){
   
    // se carga la forma como datatable
    tabla = $('#tabla').DataTable({
        "scrollY": alto_tabla(),
        "scrollX": true,
        "paging": false,
        "searching": false,
        "info": false,
        "ordering": false,
        "language": {
            "emptyTable": "No hay datos disponibles"
        }
    });

    // se ejecuta para mostrar el contenido de la tabla
    $(function(){
        $("#tabla-content").removeAttr("hidden");
        tabla.draw();
    });
    $("#tabla_filter").remove();
    
    $('#tabla tbody').on( 'click', 'tr', function () {
        if (estado == 2 || estado == 3) {
            
            $("#tabla tbody tr").removeClass('selected');
            $(this).toggleClass('selected');
            fila_select = tabla.row(this).data();
            fila_index = tabla.row(this).index();

            // si se quiere modificar, se actualizan los inputs
            if (estado == 2)
            {
                $("#fecha").prop("disabled",false);
                $("#tipo-group").show();

                $("#fecha").data("daterangepicker").setStartDate(fila_select[2]);
                $("#fecha").data("daterangepicker").setEndDate(fila_select[2]);
                $("#fecha").val(fila_select[2]);

                cargar_tipo_maquinas();

                var valor = "Collaretera";
                if (fila_select[0] == "MC") valor = "Máquina de Coser Doméstica";
                else if (fila_select[0] == "OV") valor = "Overlock";
                $("#tipo-select").text(valor);
                $("#tipo-columna").text(fila_select[0]);

                $("#vista_footer p").text("La solicitud ya esta lista para modificar");
            }
            if (estado == 3) {
                $("#vista_footer p").text("La solicitud esta lista para eliminar");
                $("#Confirmar").show();
            }
        }
    });
});

// PARA CAMBIAR DE ESTADOS ----------------------------------------------------------------------

// agregar
$("#MantAgregar").click(function(){

    // si el estado es distinto al actual
    if (estado != 1)
    {
        // se cambia el estado de la vista
        $("#vista_header p span").text("Agregar");
        $("#vista_footer p").html("Seleccione una fecha de solicitud");
        estado = 1;

        // se habilitan todos los inputs y se vuelven a desabilitar algunos
        $(".formulario .form-control").prop("disabled",false);

        // se muestra el formulario
        $("#vista_contenido .formulario").show();

        // se limpian algunos valores
        $("#fecha").val("");
        $("#tipo-select").text("");
        $("#tipo-columna").text("");

        // se oculta el dropdown de maquinas disponibles
        $("#tipo-group").hide();

        // se deseleccionan todas las filas
        $("#tabla tbody tr").removeClass('selected');
        $("#Confirmar").hide();

        // se redimensiona la tabla
        $("div.dataTables_scrollBody").height(alto_tabla());

        var fecha_actual = moment(new Date()).format("DD/MM/YYYY");
        $("#fecha").data("daterangepicker").setStartDate(fecha_actual);
        $("#fecha").data("daterangepicker").setEndDate(fecha_actual);
    }

});

// modificar
$("#MantModificar").click(function(){

    // si el estado es distinto al actual
    if (estado != 2)
    {
        // se cambia el estado de la vista
        $("#vista_header p span").text("Modificar");
        $("#vista_footer p").html("Seleccione una solicitud en la tabla");
        estado = 2;

        // se habilitan todos los inputs y se vuelven a desabilitar algunos
        $(".formulario .form-control").prop("disabled",true);

        // se muestra el formulario
        $("#vista_contenido .formulario").show();

        // se limpia el valor de la fecha de solicitud
        $("#fecha").val("");

        // se oculta el dropdown de maquinas disponibles
        $("#tipo-group").hide();

        // se deseleccionan todas las filas
        $("#tabla tbody tr").removeClass('selected');
        $("#Confirmar").hide();

        // se redimensiona la tabla
        $("div.dataTables_scrollBody").height(alto_tabla());
    }

});

// eliminar
$("#MantEliminar").click(function(){

    // si el estado es distinto al actual
    if (estado != 3)
    {
        // se cambia el estado de la vista
        $("#vista_header p span").text("Eliminar");
        $("#vista_footer p").html("Seleccione la solicitud que desea eliminar en la tabla");
        estado = 3;

        // se muestra el formulario
        $("#vista_contenido .formulario").hide();

        // se deseleccionan todas las filas
        $("#tabla tbody tr").removeClass('selected');
        $("#Confirmar").hide();

        // se redimensiona la tabla
        $("div.dataTables_scrollBody").height(alto_tabla());
    }

});

// confirmar la operacion
$("#Confirmar").click(function(){

    // si se requiere solicitar
    if (estado == 1)
    {
        // se obtiene la fecha de solicitud
        var val_fecha = $("#fecha").val();
        var f1 = val_fecha.split("/");
        val_fecha = f1[2]+"-"+f1[1]+"-"+f1[0];

        // fecha de inicio
        var val_inicio = moment(new Date()).format('YYYY-MM-DD');

        // rut de la socia
        var val_rut = login_rut.split("-")[0];
        var val_cod = $("#tipo-columna").text();

        $.ajax({
            type: "POST",
            url: url_global+"Maquina/agregar_solicitud",
            data: {
                sol_rut: val_rut,
                sol_cod: val_cod,
                sol_fecha: val_fecha,
                llave: true
            }
        }).done(function(response){
            var mensaje = JSON.parse(response);
            if (mensaje[0] == 1)
            {
                $("#vista_footer p").text("Su solicitud ha sido enviada con éxito");
                tabla.row.add([
                    val_cod,
                    mensaje[1][0],
                    $("#fecha").val(),
                    "Pendiente",
                    mensaje[1][1]
                ]).draw(false);
            }
            else {
                mensaje_error(mensaje[1]);
            }
        });
    }
    // si se requiere modificar
    else if (estado == 2)
    {
        // rut de la socia
        var val_rut = login_rut.split("-")[0];

        var val_fecha = fila_select[2];
        var f1 = val_fecha.split("/");
        val_fecha = f1[2]+"-"+f1[1]+"-"+f1[0];

        // se elimina la solicitud
        $.ajax({
            type: "POST",
            url: url_global+"Maquina/eliminar_solicitud",
            data: {
                sol_rut: val_rut,
                sol_cod: fila_select[0],
                sol_num: fila_select[1],
                sol_fecha: val_fecha,
                sol_turno: fila_select[4],
                llave: true
            }
        }).done(function(response){

            var msg1 = JSON.parse(response);
            // si se pudo eliminar
            if (msg1[0] == 1) {

                // se obtiene la fecha de solicitud
                val_fecha = $("#fecha").val();
                f1 = val_fecha.split("/");
                val_fecha = f1[2]+"-"+f1[1]+"-"+f1[0];

                // se obtiene el tipo de maquina
                var val_cod = $("#tipo-columna").text();

                // se agrega una nueva solicitud
                $.ajax({
                    type: "POST",
                    url: url_global+"Maquina/agregar_solicitud",
                    data: {
                        sol_rut: val_rut,
                        sol_cod: val_cod,
                        sol_fecha: val_fecha,
                        llave: true
                    }
                }).done(function(response){
                    var msg2 = JSON.parse(response);
                    if (msg2[0] == 1)
                    {
                        $("#vista_footer p").text("Su solicitud ha sido enviada con éxito");
                        tabla.row(fila_index).data([
                            val_cod,
                            msg2[1][0],
                            $("#fecha").val(),
                            "Pendiente",
                            msg2[1][1]
                        ]).draw();

                        fila_select = [
                            val_cod,
                            msg2[1][0],
                            $("#fecha").val(),
                            "Pendiente",
                            msg2[1][1]
                        ];
                    }
                    else {
                        mensaje_error(msg2[1]);
                    }
                });
            } else {
                mensaje_error("La solicitud no se pudo modificar");
            }
        });
    }
    // si se requiere eliminar
    else if (estado == 3)
    {
        mensaje_confirmar(
            "Confirmar eliminación",
            "Esta segura que desea eliminar la solicitud?",
        function(){

            // rut de la socia
            var val_rut = login_rut.split("-")[0];

            var val_fecha = fila_select[2];
            var f1 = val_fecha.split("/");
            val_fecha = f1[2]+"-"+f1[1]+"-"+f1[0];

            // se elimina la solicitud
            $.ajax({
                type: "POST",
                url: url_global+"Maquina/eliminar_solicitud",
                data: {
                    sol_rut: val_rut,
                    sol_cod: fila_select[0],
                    sol_num: fila_select[1],
                    sol_fecha: val_fecha,
                    sol_turno: fila_select[4],
                    llave: true
                }
            }).done(function(response){

                // si se pudo eliminar
                var mensaje = JSON.parse(response);
                if (mensaje[0] == 1) {
                    $("#vista_footer p").text(mensaje[1]);
                    tabla.row(fila_index).remove().draw();
                } else {
                    mensaje_error(mensaje[1]);
                }

            });
        },function(){
            $("#vista_footer p").text("Se canceló la operación de eliminación");
        });            
    }

});