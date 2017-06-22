var estado = 0;

$(document).ready(function(){
    actualizar_eventos();
})

// volver a la vista de notificaciones
$("#notif-nav").click(function(){
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
});

// si se presiona enviar
$(".mi-resp-btn-enviar").click(function(){

    // se obtiene el contenido de la respuesta
    var resp_desc = $(".mi-resp").find("textarea").val();

    if (resp_desc != ""){

        var fecha_resp = moment(new Date()).format('YYYY-MM-DD');
        var nombre_resp = $("#principal_bienvenido span").text();
        estado=0;
        $.ajax({
            type:"POST",
            url: url_global+"Notificacion/agregar_respuesta",
            data:{
                cod_not: notificacion,
                rut: rut,
                descripcion: resp_desc,
                fecha : fecha_resp,
                llave: true
            }
        }).done(function(response){
            var mensaje = JSON.parse(response);
            if (mensaje[0] >= 0)
            {
                $("#vista_footer p").text(mensaje[1]);

                f_arr = fecha_resp.split("-");
                fecha_resp = f_arr[2]+"/"+f_arr[1]+"/"+f_arr[0];

                var mi_html = '<div class="resp panel panel-default"'; 
                mi_html += 'style="font-size:30px;"'; 
                mi_html += 'resp-num="'+mensaje[0]+'">';
                mi_html += '<div class="panel-heading container-fluid" style="padding: 10px 15px;">';
                mi_html += '<div class="row">';
                mi_html += '<div class="col-md-9">';
                mi_html += '<b><span class="resp-nom">'+nombre_resp+'</span></b>';
                mi_html += '</div>';
                mi_html += '<div class="col-md-3 pull-right" style="text-align: right;">';
                mi_html += '<b>Fecha:</b> <span class="resp-fecha">'+fecha_resp+'</span>';
                mi_html += '</div>';
                mi_html += '</div>';
                mi_html += '</div>';
                mi_html += '<div class="panel-body container-fluid" style="text-align: justify;">';
                mi_html += '<div class="row">';
                mi_html += '<div class="col-md-12 resp-desc text-justify">';
                mi_html += '<Textarea disabled style="resize:none;';
                mi_html += 'width: 100%;';
                mi_html += 'border: 0;';
                mi_html += 'background: #fff;">'+resp_desc+'</Textarea>';
                mi_html += '</div>';
                mi_html += '</div>';
                mi_html += '</div>';
                mi_html += '<div class="panel-footer container-fluid resp-opciones">';
                mi_html += '<button type="button" class="btn btn-primary pull-right resp-btn-modificar">Modificar</button>';
                mi_html += '<button type="button" class="btn btn-danger pull-right resp-btn-eliminar">Eliminar</button>';
                mi_html += '</div>';
                mi_html += '</div>';

                $(".notif-respuestas").append(mi_html);
                actualizar_eventos();
                $(".mi-resp").find("textarea").val("");
            }
            else {
                mensaje_error(mensaje[1]);
            }
        });
    }else{
        $("#vista_footer p").text("por favor ingrese la respuesta");
        $(".mi-resp").find("textarea").focus();
        $(".mi-resp").find(".form-group").addClass("has-error");
        $(".mi-resp").find("textarea").css({"background-color":"#FDCDCD"});
    }
});

function actualizar_eventos() {

    // para eliminar una respuesta
    $(".resp-btn-eliminar").click(function(){

        var mi_respuesta = $(this).parents(".resp");
        var mi_id = mi_respuesta.attr("resp-num");
        if (estado == 0)
        {
            mensaje_confirmar(
                "Confirmar eliminación",
                "Esta segura que desea eliminar su respuesta?",
            function(){
                // se hace la peticion ajax para eliminar
                $.ajax({        
                    type: "POST",
                    url: url_global+"Notificacion/eliminar_respuesta",
                    data: {
                       cod_not: notificacion,
                       cod_resp: mi_id,
                       llave: true
                    }
                }).done(function(response){
                    if (response == 1){
                        mi_respuesta.remove();
                        $("#vista_footer p").text("La respuesta se eliminó exitosamente del sistema");
                    }
                    else{
                        $("#vista_footer p").text(response);
                    }
                });
            });
        }
        else
        {
            $(this).text("Eliminar");
            mi_respuesta.find(".resp-btn-modificar").text("Modificar");
            mi_respuesta.find("textarea").attr("disabled",true);
            mi_respuesta.find("textarea").val(mi_respuesta.find(".resp-guarda").val());
            mi_respuesta.find(".resp-guarda").val("");
            estado = 0;
        }   
    });

    // para modificar una respuesta
    $(".resp-btn-modificar").click(function(){
        var mi_respuesta = $(this).parents(".resp");
        var mi_id = mi_respuesta.attr("resp-num");
        if (estado == 0)
        {
            $(this).text("Guardar");
            mi_respuesta.find(".resp-btn-eliminar").text("Cancelar");
            mi_respuesta.find("textarea").attr("disabled",false);
            mi_respuesta.find(".resp-guarda").val(mi_respuesta.find("textarea").val());
            mi_respuesta.find("textarea").focus();
            estado = 1;
        }
        else
        {
            $.ajax({
                type:"POST",
                url: url_global+"Notificacion/editar_respuesta",
                data:{
                    cod_not: notificacion,
                    cod_resp: mi_id,
                    descripcion: mi_respuesta.find("textarea").val(),
                    llave: true
                }
            }).done(function(response){
                var mensaje = JSON.parse(response);
                if (mensaje[0] == 1){
                    $("#vista_footer p").text(mensaje[1]);
                    mi_respuesta.find(".resp-btn-modificar").text("Modificar");
                    mi_respuesta.find(".resp-btn-eliminar").text("Eliminar");
                    mi_respuesta.find("textarea").attr("disabled",true);
                    mi_respuesta.find(".resp-guarda").val("");
                    estado = 0;
                }else{
                    mensaje_error(mensaje[1]);
                }
            });
        }
    });

}