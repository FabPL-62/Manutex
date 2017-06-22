$(function() {
    var datepicker = $('#trans_fecha').daterangepicker({
        singleDatePicker: true,
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

    $("#trans_fecha").data('daterangepicker').setStartDate(moment(new Date()).format('DD/MM/YYYY HH:mm'));
    //$("#trans_fecha").data('daterangepicker').setEndDate(moment(new Date()).format('DD/MM/YYYY HH:mm'));
});

// 1: agregar
// 2: modificar
// 3: consultar
var estado = 1;
// obtener alto para la tabla
function alto_tabla(){
    if (estado >= 3) return $("#vista_contenido").height()-100;
    else return Math.max($("#vista_contenido").height()-$(".formulario").height()-100,250);
}

var tabla;
var fila_select;
var fila_index = -1;
$(document).ready(function(){
    $('#tabla tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Buscar por '+title+'" />' );
    } );
   
    // se carga la forma como datatable
    tabla = $('#tabla').DataTable({
        "scrollY": alto_tabla(),
        "scrollX": true,
        "paging": false,
        "searching": true,
        "info": false,
        "language": {
            "emptyTable": "No hay datos disponibles"
        },     
        "createdRow": function ( row, data, index ) {
            if ( data[2] < 0) {
                $(row).css({"background-color":"#FDCDCD"}).addClass('highlight');
            }
        }
    });

    // se ejecuta para mostrar el contenido de la tabla
    $(function(){
        $("#tabla-content").removeAttr("hidden");
        tabla.draw();
    });

    $("#tabla_filter").remove();
    
    $('#tabla tbody').on( 'click', 'tr', function () {
        if (estado == 2) {
            
            $("#tabla tbody tr").removeClass('selected');
            $(this).toggleClass('selected');
            fila_select = tabla.row(this).data();
            fila_index = tabla.row(this).index();
            if (parseInt(fila_select[2]) > 0) {
                $("#trans_tipo").val("1");
            } else {
                $("#trans_tipo").val("-1");
            }
            $("#trans_monto").val(Math.abs(parseInt(fila_select[2])));
            $("#trans_fecha").val(fila_select[1]);
            $("#trans_descrip").val(fila_select[3]);
            $("#vista_header p").text("Modificar Transacción : N°"+fila_select[0]);
        }
    });

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

var formulario_h = $("#vista_contenido .formulario").height();

/*// si se escribe un rut correcto muestra el nombre de la socia
// si se escribe un rut, se desactiva el tipo, ya que solo puede ser ingreso

var rut_encontrado = false;
$("#trans_rut").on("input",function(){
    var mi_rut = $("#trans_rut").val();
    var encontro= false;
    for (var i in socias) {
        var socia = socias[i];
        if (mi_rut == socia[0]) {
            $("#nom-socia").text(socia[1]+" "+socia[2]+" "+socia[3]);
            encontro = true;
            break;
        }
    }
    if (encontro == false) {
        $("#nom-socia").text("");
        $("#trans_tipo").prop("disabled",false);
        if (rut_encontrado == true) {
            rut_encontrado = false
            $("#trans_rut").val("");
        }
    } else {
        rut_encontrado = true;
        $("#trans_tipo").val("1");
        $("#trans_tipo").prop("disabled",true);
    }
});
$("#trans_rut").focusout(function(){
    if (rut_encontrado == false) {
        $("#trans_rut").val("");
    }
});


// desactivar rut si es egreso
$("#trans_tipo").change(function(){
    var val = $("#trans_tipo option:selected").val();
    if (val == "-1") {
        $("#trans_rut").prop("disabled",true);
    } else {
        $("#trans_rut").prop("disabled",false);
    }
});*/

$("#MantAgregar").click(function(){
    estado = 1;
    $("#trans_monto").val("");
    $("#trans_descrip").val("");   
    $("#trans_descrip").removeClass("has-error"); 
    $("#trans_descrip").css({"background-color":""});
    $("#trans_monto").removeClass("has-error"); 
    $("#trans_monto").css({"background-color":""}); 
    $(".formulario").show(); 
    $("#vista_contenido .formulario").height(formulario_h);
    $("#tabla tbody tr").removeClass('selected');
    $("#vista_header p").text("Agregar Transacción : Nº"+ ultima);
    $("#vista_footer p").html("");
    $("div.dataTables_scrollBody").height(alto_tabla());
    $("#Confirmar").text("Confirmar");

});

$("#MantModificar").click(function(){
    estado = 2;
    $("#tabla tbody tr").removeClass('selected');
    $("#trans_monto").val("");
    $("#trans_descrip").val("");
    $("#trans_descrip").removeClass("has-error"); 
    $("#trans_descrip").css({"background-color":""});
    $("#trans_monto").removeClass("has-error"); 
    $("#trans_monto").css({"background-color":""});  
    $(".formulario").show();
    $("#vista_contenido .formulario").height(formulario_h);  
    $("#vista_header p").text("Modificar Transacción");
    $("#vista_footer p").html("Seleccione una Transacción en la tabla para modificar");       
    $("div.dataTables_scrollBody").height(alto_tabla()); 
    $("#Confirmar").text("Confirmar");  
});

$("#MantConsultar").click(function(){
    estado = 3;
    $("#tabla tbody tr").removeClass('selected');
    $(".formulario").hide();
    $("div.dataTables_scrollBody").height(alto_tabla());
    $("#vista_header p").text("Transacción : Consultar ");
    $("#vista_footer p").html("");
    $("div.dataTables_scrollBody").height(alto_tabla());
    $("#Confirmar").text("Generar informe");
});

$("#Confirmar").click(function(){
    $("#trans_descrip").removeClass("has-error"); 
    $("#trans_descrip").css({"background-color":""});
    $("#trans_monto").removeClass("has-error"); 
    $("#trans_monto").css({"background-color":""}); 
    if (estado == 1){
        var fecha = $("#trans_fecha").val();
        var monto = $("#trans_monto").val();
        if (monto != ""){
            var desc = $("#trans_descrip").val();
            if (desc != ""){
                var tipo = $("#trans_tipo").val();   
                monto = monto*tipo;  
                $.ajax({
                    type : "POST",
                    url : url_global+"Transaccion/agregar",
                    data : {
                        fecha : fecha,
                        monto : monto,
                        desc : desc
                    }
                }).done(function(response){
                    var numero = parseInt(response);
                    if (numero == 1){

                        $("#trans_monto").val("");
                        $("#trans_descrip").val(""); 
                        $("#vista_footer p").html("La Transaccion a sido ingresada con exito"); 
                        $("#trans_tipo").val(1);        
                        tabla.row.add([
                                ultima,
                                fecha,
                                monto,
                                desc
                            ]).draw(false);
                        $("#id").text(numero + 1);
                        ultima = ultima +1;
                        $("#vista_header p").text("Agregar Transacción : Nº"+ ultima);

                        if (monto > 0) 
                        {
                            // var comprobante = {
                            //     tipo: 0,
                            //     fecha: fecha,
                            //     numero: numero,
                            //     titulo: "Comprobante de ingreso",
                            //     monto: monto,
                            //     descripcion: desc
                            // };
                            // generar_comprobante(comprobante);
                        }
                        
                   }
                   else $("#vista_footer p").html(response);
                });
            }else{
               $("#trans_descrip").addClass("has-error"); 
               $("#trans_descrip").css({"background-color":"#FDCDCD"});
            }     
        } else{
            $("#trans_monto").addClass("has-error"); 
            $("#trans_monto").css({"background-color":"#FDCDCD"});  
        }
        
    }
    else { 
        if (estado == 2){
            var fecha = $("#trans_fecha").val();
            var monto = $("#trans_monto").val();
            var num = fila_select[0];
            if (monto != ""){
                var desc = $("#trans_descrip").val();
                if (desc != ""){
                    var tipo = $("#trans_tipo").val();   
                    monto = monto*tipo;          
                    $.ajax({
                        type : "POST",
                        url : url_global+"Transaccion/modificar",
                        data : {
                            fecha : fecha,
                            num : num,
                            monto : monto,
                            desc : desc
                        }
                    }).done(function(response){
                        if (response == 1){
                            alert(fila_index);
                            $("#trans_monto").val("");
                            $("#trans_descrip").val(""); 
                            $("#vista_footer p").html("La Transaccion a sido modificada con exito"); 
                            fila_select[1] = fecha;
                            fila_select[2] = monto;
                            fila_select[3] = desc;
                            tabla.row(fila_index).data(fila_select).draw();
                       }
                       else $("#vista_footer p").html("Error");  
                    });
                }else{
                   $("#trans_descrip").addClass("has-error"); 
                   $("#trans_descrip").css({"background-color":"#FDCDCD"});
                }     
            }
            else{
                $("#trans_monto").addClass("has-error"); 
                $("#trans_monto").css({"background-color":"#FDCDCD"});  
            }
        }
        else {
            if (estado == 3){

                // tabla que se enviara a pdfmake para generar el documento
                var mi_tabla = [];

                // arreglo de anchos de las columnas
                var mi_anchos = ["auto","auto","auto","*"];

                // se agregan las cabeceras a la tabla
                var mi_cabecera = [
                    {text:"Nº Transacción",bold:true,fillColor:"#DBDBDB"},
                    {text:"Fecha",bold:true,fillColor:"#DBDBDB"},
                    {text:"Monto",bold:true,fillColor:"#DBDBDB"},
                    {text:"Descripción",bold:true,fillColor:"#DBDBDB"}
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
                                    text: "Listado de Transacciones",
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
                    $("#vista_footer p").text("No hay Transacciones seleccionadas para generar el informe");
                }
            }
        }

    }
 
});


//para que el input solo acepte numeros
numero_keypress("#trans_monto");