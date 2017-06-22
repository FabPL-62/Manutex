// obtener alto para la tabla
function alto_tabla() {
    return $("#vista_contenido").height()-140;
}

// el año anterior es igual al año actual
var año_ant = año_actual;

var tabla;
$(document).ready(function() { 

    $("table.dataTable.hover tbody tr:hover").css({"background-color":"#C2C2C2"});
    $('#tabla tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Buscar por '+title+'" />' );
    } );
   
    // se carga la forma como datatable
    tabla = $('#tabla').DataTable({
        "scrollY": alto_tabla(),
        "scrollX": true,
        "paging": false,
        "searching": false,
        "ordering": false,
        "info": false,
        "language": {
            "emptyTable": "No hay asistencias para el presente año"
        }
    });

    // se ejecuta para mostrar el contenido de la tabla
    $(function(){
        $("#tabla-content").removeAttr("hidden");
        tabla.draw();
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

// el ingreso del año solo acepta numeros
$("#año-asistencia").keypress(function(e){
    return false;
});

// cuando cambia el año, se restringe que salga de un rango
$("#año-asistencia").on("input",function(){

    // se obtiene el nuevo valor
    var nuevo = parseInt($(this).val());

    // si el nuevo es mayor que el maximo, se le resta 1
    if (nuevo > año_actual) {
        nuevo = año_actual;
    }
    if (nuevo < 2000) {
        nuevo = 2000;
    }

    // se asigna el valor
    $("#año-asistencia").val(nuevo);
});

// si se presiona confirmar
$("#año-confirmar").on("click",function(){
    var año = $("#año-asistencia").val();
    if (año != "") {

        // si el año es diferente al anterior
        if (año != año_ant) {

            $(".form-group").removeClass("has-error");
            $("#año-asistencia").css({"background-color":""});

            // se hace una peticion para volver a llenar la tabla
            $.ajax({
                type: "POST",
                url: url_global+"Evento/consultar_eventos_socia",
                data: {
                    rut: login_rut,
                    año: año,
                    llave: true
                }
            }).done(function(response) {
                var mensaje = JSON.parse(response);
                if (mensaje[0] == 1) {
                    tabla.clear();
                    tabla_actual = [];
                    var t = 0, c = 0;
                    for (var i in mensaje[1]) {
                        var socia = mensaje[1][i];
                        tabla_actual.push(socia.slice());
                        if (socia[2] == 0) {
                            socia[2] = "<span class='glyphicon glyphicon-remove'></span>";
                        } else {
                            socia[2] = "<span class='glyphicon glyphicon-ok'></span>";
                            c += 1;
                        }
                        tabla.row.add(socia);
                        t += 1;
                    }
                    var r = Math.round(c/t*100);
                    $("#vista_footer p span").text(r+"%");
                    tabla.draw();
                    año_ant = año;
                    $("#vista_header p span").text(año);
                } else {
                    mensaje_error(mensaje[1]);
                }
            });

        }
    } else {
        $(this).parents(".form-group").addClass("has-error");
        $("#año-asistencia").css({"background-color":"#FDCDCD"});
        $("#vista_footer p").text("Ingrese el año de consulta");
    }
});

// si se presiona generar informe
$("#Generar").click(function(){

    // vemos si la tabla actual tiene datos
    if (Array.isArray(tabla_actual))
    {
        // tabla que se enviara a pdfmake para generar el documento
        var mi_tabla = [];

        // se agregan las cabeceras a la tabla
        var mi_cabecera = [
            {text:"Tipo de evento",bold:true,fillColor:"#DBDBDB"},
            {text:"Fecha de evento",bold:true,fillColor:"#DBDBDB"},
            {text:"Asistió",bold:true,fillColor:"#DBDBDB"}
        ];
        mi_tabla.push(mi_cabecera);

        // se recorre el arreglo
        for (var i in tabla_actual) 
        {
            // se obtiene la fila
            var fila = tabla_actual[i];
            var mi_fila = [];

            // se recorre la fila
            for (var j in fila) 
            {
                // se obtiene el elemento
                var mi_elemento = fila[j];

                if (j != 2) {
                    mi_fila.push(mi_elemento);
                } else {
                    if (mi_elemento == "1") {
                        mi_elemento = {text:"*",alignment:'center'};
                        mi_fila.push(mi_elemento);
                    }
                    else {
                        mi_elemento = "";
                        mi_fila.push(mi_elemento);
                    }
                }
            }
            mi_tabla.push(mi_fila);
        }
        var porcentaje = $("#vista_footer p span").text().trim();

        // definicion del documento
        var docDefinition = { 
            pageMargins: [40, 80, 40, 40],
            header: [
                {
                    margin: 20,
                    columns: [
                        [
                            {
                                text: "Total Asistencia Año "+año_ant+" : "+porcentaje,
                                bold: true
                            },
                            {
                                text: [
                                    {text: "Nombre Completo : ",bold:true},
                                    {text: nombre, decoration: 'underline'}
                                ]
                            },
                            {
                                text: [
                                    {text: "Rut : ",bold:true},
                                    {text: login_rut, decoration: 'underline'}
                                ]
                            }
                        ],   
                        {
                            text:"Fecha: " +moment(new Date()).format("DD/MM/YYYY"),
                            alignment:"right",
                            bold:true
                        }
                    ]
                }
            ],
            content: [
                {
                    style: 'style_table',
                    table: {
                        widths: ['*', '*', 'auto'],
                        headerRows: 1,
                        body: mi_tabla
                    }
                }
            ],
            footer: function(page, pages) { 
                return { 
                    columns: [ 
                        '(*) Indica que si asistió al evento',
                        { 
                            alignment: 'right',
                            text: [
                                { text: page.toString(), italics: true },
                                ' / ',
                                { text: pages.toString(), italics: true }
                            ]
                        }
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
    else {
        mensaje_error(tabla_actual);
    }
});