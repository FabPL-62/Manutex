// funcion para sincronizar
// $(document).ready(function(){
//     setInterval("sincronizar()",1000);
// });
// var vcc = 0;
// function sincronizar()
// {
    
    // var mi_vista_estado = $("#vista_header p").text();
    // if (mi_vista_estado == "Notificaciones")
    // {
    //     // se obtienen las notificaciones de la base de datos
    //     $.ajax({
    //         type:"POST",
    //         url:url_global+"Notificacion/consultar_notificaciones",
    //         data:{
    //             rut: login_rut
    //         }
    //     }).done(function(response){
    //         $("#principal_vista").load(
    //             url_global+"vistas/Notificacion/index.php",
    //             {
    //                 notificacion: response,
    //                 login_rut: login_rut,
    //                 login_pass: login_pass
    //             }
    //         );
    //         // var sinc_notificaciones = JSON.parse(response);
    //         // for (var i in notificaciones) {
    //         //     var notif = notificaciones[i];
    //         //     for (var j in sinc_notificaciones) {
    //         //         var sinc_notif = sinc_notificaciones[j];

    //         //         // vemos si hay alguna coincidencia
    //         //         var sinc_coincidencia = 0;
    //         //         if (notif[0] == sinc_notif[0]) {
    //         //             sinc_coincidencia += 1;
    //         //             var distinto = false;
    //         //             for (var k in notif) {
    //         //                 var n_elem = notif[k];
    //         //                 for (var l in sinc_notif) {
    //         //                     var sn_elem = sinc_notif[l];
    //         //                     distinto = (distinto || (n_elem != sn_elem));
    //         //                 }
    //         //             }
    //         //             if (distinto == true) {
    //         //                 notificaciones[i] = sinc_notificaciones[j];
    //         //                 break;
    //         //             }
    //         //         }
    //         //         if (sinc_coincidencia == 0) {

    //         //         }
    //         //     }
    //         // }
    //     });
    // }
//     $("#vista_header p").text(vcc);
//     vcc++;
// }

// si se presiona agregar
$("#notif-nav").click(function(){

    // obtenemos el estado
    var mi_estado = $(this).text();

    // si deseamos agregar una nueva notificacion
    if (mi_estado == "Agregar")
    {
        // se hace la peticion ajax para cargar la tabla de socias
        $.ajax({
            type: "POST",
            url: url_global+"Socia/lista_consultar2",
            data: {
                rut: login_rut,
                llave: true
            }
        }).done(function(response) {

            var enviar_estado = JSON.stringify([0,""]);
            $('#vista_contenido').load(
                url_global+"Principal/cargar/Enviar",
                {
                    login_rut : login_rut,
                    tabla: response,
                    estado: enviar_estado,
                    llave: true
                }
            );

            // se le asigna un padding de 0
            $("#vista_contenido").css({
                "padding" : "0px"
            });

            $("#vista_header p").text("Notificaciones : Agregar");
            $("#vista_footer p").text("Complete los campos requeridos (*) y seleccione al menos una socia para agregar una nueva notificación");

            // se cambia el estado a volver
            $("#notif-nav").text("Volver");
        });
    }
    else if (mi_estado == "Volver")
    {
        // se carga la pagina enviandole la tabla de socias a la sub vista
        $.ajax({
            type:"POST",
            url:url_global+"Notificacion/consultar_notificaciones",
            data:{
                rut: login_rut,
                llave: true
            }
        }).done(function(response){
            $("#principal_vista").load(
                url_global+"Principal/cargar/Notificacion",
                {
                    notificacion: response,
                    login_rut: login_rut,
                    login_pass: login_pass,
                    llave: true
                }
            );
        });
    }

});

$(".notif-btn-modificar").click(function(){

    var mi_notificacion = $(this).parents(".notif");
    var mi_codigo = parseInt(mi_notificacion.attr('cod-notif'));
    var mi_asunto = mi_notificacion.find(".notif-asunto").text().trim();
    var mi_contenido = mi_notificacion.find(".notif-desc").text().trim();

    $.ajax({
        type: "POST",
        url: url_global+"Notificacion/consultar_destinatarios",
        data : {
            codigo : mi_codigo,
            rut : login_rut.split("-")[0],
            llave: true
        }
    }).done(function(response){

        var enviar_estado = JSON.stringify([1,[mi_codigo,mi_asunto,mi_contenido]]);
        $('#vista_contenido').load(
            url_global+"Principal/cargar/Enviar",
            {
              login_rut : login_rut,  
              tabla: response,
              estado: enviar_estado,
              llave: true
            }
        );

        // se le asigna un padding de 0
        $("#vista_contenido").css({
            "padding" : "0px"
        });

        $("#vista_header p").text("Notificaciones : Modificar");
        $("#vista_footer p").text("Complete los campos requeridos (*) y seleccione al menos una socia para modificar la notificación");
        $("#Confirmar").show();

        // se cambia el estado a volver
        $("#notif-nav").text("Volver");

    });
});

// si se presiona responder
$('.notif-btn-responder').click(function(){

    var mi_notificacion = $(this).parents(".notif");
    var mi_codigo = parseInt(mi_notificacion.attr('cod-notif'));
    var mi_id = parseInt(mi_notificacion.attr('id-array'));

    // obtenemos los datos de la notificacion
    var notif = JSON.stringify(notificaciones[mi_id]);

    $.ajax({
        type:"POST",
        url: url_global+"Notificacion/listar_respuesta",
        data: {
            codigo: mi_codigo,
            llave: true
        }
    }).done(function(response){
        $('#principal_vista').load(
        url_global+"Principal/cargar/Responder",
        {
            notif_contenido: notif,
            notif_respuesta:response,
            rut: login_rut,
            llave: true
        });
    });  
});

// si se presiona eliminar
$(".notif-btn-eliminar").click(function(){
    var mi_notificacion = $(this).parents(".notif");
    var mi_codigo = parseInt(mi_notificacion.attr('cod-notif'));
    mensaje_confirmar(
        "Confirmar eliminación",
        "Esta segura que desea eliminar la notificación?",
    function(){
        $.ajax({
            type:"POST",
            url: url_global+"Notificacion/eliminar_notificacion",
            data: {
                cod : mi_codigo,
                llave: true
            }
        }).done(function(response){
            var mensaje = JSON.parse(response);
            if (mensaje[0] == 1){
                mi_notificacion.remove();
                $("#vista_footer p").text(mensaje[1]);
            }
            else{
                mensaje_error(mensaje[1]);
            }
        });
    },function(){
        $("#vista_footer p").text("Se canceló la operación de eliminación");
    });
});