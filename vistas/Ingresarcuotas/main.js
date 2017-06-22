var tabla;
var fecha=moment(new Date()).format("YYYY-MM-DD");
// obtener alto para la tabla
function alto_tabla() {
    return $("#vista_contenido").height()-$(".formulario").height()-100;
}

// obtener nombre del mes
function mes_nombre(numero)
{
    var meses = [
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
    return meses[numero-1];
}

$(document).ready(function(){
    $('#tabla tfoot th').each( function (i,v) {
        if (i < 2)
        {
            var title = $(this).text();
            if (i == 0) {
                $(this).html('<input type="text" placeholder="'+title+'" style="width:100px;"/>');
            } else {
                $(this).html( '<input type="text" placeholder="'+title+'" />' );
            }
        }  
    });

    // se carga la forma como datatable
    tabla = $('#tabla').DataTable({
        "scrollY": alto_tabla(),
        "scrollX": true,
        "autoWidth": false,  
        "paging": false,
        "searching": true,
        "info": false,
        "ordering": false,
        "language": {
            "emptyTable": "No hay datos disponibles"
        },
        "createdRow": function ( row, data, index ) {
            for (var i=2; i<=13; i++){
                if ( data[i] == "") {
                    $('td', row).eq(i).append("<button class='btn btn-danger btn-pagar' style='width:100px;'>Pagar</button>");
                }else{
                    $('td', row).eq(i).css({"background-color":"rgba(181, 235, 170, 0.49)"});
                }
            }
        }
    });

    $(".btn-pagar").click(function(){

        if (estado == 1)
        {
            // obtenemos la fila
            var mi_tr = $(this).parents("tr");

            // obtenemos los datos de la celda
            var mi_td = $(this).parent();
            var mi_mes = parseInt(mi_td.attr("mes"));
            var mi_rut = mi_tr.find(".tr-rut").text();
            var mi_nom = mi_tr.find(".tr-nombre").text();

            // recorremos las celdas de la fila
            var mis_meses = [];
            mi_tr.find(".td-mes").each(function(i,v){
                var loc_mes = parseInt($(this).attr("mes"));
                var loc_color = $(this).css("background-color");
                if (loc_mes <= mi_mes) {
                    if (loc_color == "transparent") {
                        mis_meses.push(loc_mes);
                    }
                }
            });

            // obtenemos el valor mensual y de ingreso
            var val_mensual = parseInt($("#valor_mensual").val());
            var val_ingreso = parseInt($("#valor_ingreso").val());

            var total = 0;
            var cuotas = "";

            for (var i in mis_meses)
            {
                var loc_mes = mis_meses[i];
                if (loc_mes == 0) {
                    cuotas += "Cuota de Ingreso";
                    total += val_ingreso;
                } else {
                    total += val_mensual;
                    if (cuotas == "Cuota de Ingreso") {
                        if (i == mis_meses.length-1) {
                            cuotas += ", Cuota de "+mes_nombre(loc_mes);
                        } else {
                            cuotas += ", Cuotas de "+mes_nombre(loc_mes);
                        }                        
                    } else {
                        if (cuotas == "") {
                            if (i == mis_meses.length-1) {
                                cuotas += "Cuota de "+mes_nombre(loc_mes);
                            } else {
                                cuotas += "Cuotas de "+mes_nombre(loc_mes);
                            }
                        } else {
                            if (i == mis_meses.length-1) {
                                cuotas += " a "+mes_nombre(loc_mes);
                            }
                        }
                    }
                }
            }
            $("#pago-socia-rut").text(mi_rut);
            $("#pago-socia-nom").val(mi_nom);
            $("#pago-cuotas").val(cuotas);
            $("#pago-total").val(total);
            mis_meses = JSON.stringify(mis_meses);

            $("#modal-pago").modal("show");
            
            // realizamos la peticion para pagar
            // var año = $("#año").val(); 
            // $.ajax({
            //     type:"POST",
            //     url:url_global+"Transaccion/pagar_cuotas",
            //     data:{
            //         meses: mis_meses,
            //         rut: mi_rut,
            //         fecha: fecha,
            //         año: año
            //     }
            // }).done(function(response){
            //     alert(response);
            // });
        }  
    });
    
    $("#tabla_filter").remove();

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


// ingresar un pagode una couta 1
//cambiar el valor de las cuotas del año 2
var estado = 1;

$("#MantPagar").click(function(){
    if (estado != 1){
        $("#valor_mensual").prop("disabled",true);  
    $("#valor_ingreso").prop("disabled",true);
        estado = 1;
    }
});

$("#MantCambiar").click(function(){
    if (estado != 2){
        estado = 2;
    $("#valor_mensual").prop("disabled",false);
    $("#valor_ingreso").prop("disabled",false); 
    }
});


$("#MantConfirmar").click(function(){
    if (estado == 1){
        var año = $("#año").val();
    $.ajax({
        type:"post",
         url: url_global+"Transaccion/pagar",
         data:{
            rut:rut,
            fecha:fecha,
            año:año
         }
    }).done(function(response){

    });
    }else if (estado == 2){
       var año = $("#año").val();
       var mensual = $("#valor_mensual").val();
       var ingreso = $("#valor_ingreso").val();
       $.ajax ({
            type: "POST",
            url: url_global+"Transaccion/agregar_valor_anual",
            data: {
                año:año,
                mensual:mensual,
                ingreso:ingreso
            }
       }).done(function(response){
         $("#vista_footer").text("se ha ingresado exitosamente");
       });
    }
});

//date picker
$(function() {
    $('#pago-fecha').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        timePicker:false,
        maxDate: new Date(),
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

/*$("#año-confirmar").click(function(){

    var año = $("#año").val();
    if (año != "") {

        $(".form-group").removeClass("has-error");
        $("#año-año").css({"background-color":""});

        // se hace la peticion para modificar una asistencia por si hubo algun error
        $.ajax({
            type: "POST",
            url: url_global+"Evento/consultar_eventos_anual",
            data: {
                año: año
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

});*/
