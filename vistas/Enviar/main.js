// 0: agregar
// 1: modificar

// // obtener alto para la tabla
function alto_tabla() {
    return $("#vista_contenido").height()-100;
}

var tabla;
var fila_select;
var fila_index = -1;
$(document).ready(function(){
   
    // se carga la forma como datatable
    tabla = $('#tabla-dest').DataTable({
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
    
    $('#tabla-dest tbody').on( 'click', 'tr', function () {
        if ($(this).hasClass("selected")) {
            var contador = 0;
            $("#tabla-dest tbody tr").each(function(){
                if ($(this).hasClass('selected')) {
                    contador += 1;
                }
            });
            if (contador == 1) {
                $("#Confirmar").hide();
            }
        } else {
            $("#Confirmar").show();
        }
        $(this).toggleClass("selected");
    });
});

var seleccionar_todas = false;
$(".btn-sel-todo").click(function(){
    if (seleccionar_todas == false) {
        $('#tabla-dest tbody tr').addClass("selected");
        $("#Confirmar").show();
        $(this).text("Deseleccionar Todas");
        seleccionar_todas = true;
    } else {
        $('#tabla-dest tbody tr').removeClass("selected");
        $(this).text("Seleccionar Todas");
        $("#Confirmar").hide();
        seleccionar_todas = false;
    }
});

// cuando se redimensiona la ventana del navegador
$(window).resize(function(){
    $("div.dataTables_scrollBody").height(alto_tabla());
});

// confirmar operaciones----------------------------------------------------------------------------
$("#Confirmar").click(function(){

    $(".form-group").removeClass("has-error");
    $(".form-control").css({"background-color":""});

    var asunto = $("#notif-asunto").val();
    var contenido = $("#notif-desc").val();
    var error = false;
    $(".form-control").each(function(i,v){
        var contenido = $(this).val();
        if (contenido == "") {

            if (error == false) {
                $(this).focus();
                error = true;
            }

            $(this).parents(".form-group").addClass("has-error");
            $(this).css({"background-color":"#FDCDCD"});

        }
    });

    if (error == false)
    {
        // se obtiene la lista de socias y se formatea a json
        var arreglo_ruts = [];
        var mi_rut='';
        $("#tabla-dest tbody tr").each(function(i,v){
            var dest = $(this).hasClass("selected");
            if (dest == true){
                mi_rut = tabla.row(this).data()[0].split("-")[0];
                arreglo_ruts.push(mi_rut);
            }
        });

        if (arreglo_ruts.length > 0)
        {
            var destinatario = JSON.stringify(arreglo_ruts);

            // si se requiere agregar
            if (vista_estado[0] == 0)
            {
                var fecha = moment(new Date()).format('YYYY-MM-DD');
                $.ajax({
                    type: "POST",
                    url: url_global+"Notificacion/agregar_notificacion",
                    data: {
                        destinatarios: destinatario,
                        fecha: fecha,
                        asunto: asunto,
                        contenido: contenido,
                        rut: login_rut.split("-")[0],
                        llave: true
                    }
                }).done(function(response){
                    var mensaje = JSON.parse(response);
                    if (mensaje[0] == 1) {
                        $("#vista_footer p").html(mensaje[1]); 
                        $("#notif-asunto").val("");
                        $("#notif-desc").val("");
                        $("#tabla-dest tbody tr").removeClass("selected");
                    } else {
                        mensaje_error(mensaje[1]);
                    }
                });              
            }
            // si se confirma el modificar
            else if (vista_estado[0] == 1)
            {
                var mi_codigo = vista_estado[1][0];
                $.ajax({
                    type: "POST",
                    url: url_global+"Notificacion/modificar_notificacion",
                    data: {
                        codigo: mi_codigo,
                        destinatarios: destinatario,
                        asunto: asunto,
                        contenido: contenido,
                        rut: login_rut.split("-")[0],
                        llave: true
                    }
                }).done(function(response){
                    var mensaje = JSON.parse(response);
                    if (mensaje[0] == 1) {
                        $("#vista_footer p").html(mensaje[1]);
                    } else {
                        mensaje_error(mensaje[1]);
                    }
                });   
            }
        }
        else {
            $("#vista_footer p").html("Seleccione al menos un destinatario"); 
        }   
    }
    else {
        $("#vista_footer p").text("Debe ingresar los campos requeridos (*)");
    }
});
