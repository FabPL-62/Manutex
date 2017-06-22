
// variable para saber en que vista estoy
var vista_actual = "Notificacion";

$(document).ready(function() {
            
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
});

// mensaje de confirmacion con modal
function mensaje_confirmar(titulo,mensaje,acepta,cancela)
{
    $("#myModal .modal-title").html('<span class="glyphicon glyphicon-info-sign"></span>  '+titulo);
    $("#myModal .modal-body").html(mensaje);
    $("#myModal .modal-footer .btn-aceptar").show();
    $("#myModal .modal-footer .btn-cancelar").text("Cancelar");
    $("#myModal").modal("show");
    // modal_accion = 0;
    if(typeof acepta !== "undefined") {
        $("#myModal .modal-footer .btn-aceptar").on("click.confirmar",function(){
            $('#myModal').off('.confirmar');
            $('#myModal').on('hidden.bs.modal.confirmar', function() {
                acepta();
                $(this).off('.confirmar');
            });
            $(".btn-cancelar,.btn-aceptar").off(".confirmar");
        });
    }
    if(typeof cancela !== "undefined") {
        $("#myModal .modal-footer .btn-cancelar, #myModal .modal-header .close, .fade").on("click.confirmar keypress.confirmar",function(){
            $('#myModal').off('.confirmar');
            $('#myModal').on('hidden.bs.modal.confirmar', function() {
                cancela();
                $(this).off('.confirmar');
            });
            $(".btn-cancelar,.btn-aceptar").off(".confirmar");
        });
    }
}

// mensaje de error con modal
function mensaje_error(mensaje)
{
    $("#myModal .modal-title").html('<span class="glyphicon glyphicon-alert"></span>   Mensaje de Error');
    $("#myModal .modal-body").html(mensaje);
    $("#myModal .modal-footer .btn-aceptar").hide();
    $("#myModal .modal-footer .btn-cancelar").text("Cerrar");
    $("#myModal").modal("show");
}

// restringir teclas que no sean numeros
function numero_keypress(selector)
{
    // para ingresar solo numeros en los input number
    $(selector).keypress(function(e){

        // si alguno se cumple, ignora la nueva entrada
        if ((e.key < '0' || e.key > '9')
        && e.charCode != 0)
            return false;
    });
}

// verificar que una fecha sea correcta
function fecha_valida(str,formato) {
    var d = moment(str,formato);
    if(d == null || !d.isValid()) return false;

    return (str.indexOf(d.format(formato)) >= 0);
}

// generar comprobante
function generar_comprobante(comprobante)
{
    // vemos el tipo de comprobante
    var tipo = comprobante.tipo;

    // cabecera
    var cabecera = {
        margin: 20,
        columns: [
            {
                image: url_global+"public/main/imagenes/manutexlogo.jpg",
                width: 40
            },
            [
                {
                    text: "Agrupación MANUTEX",
                    fontSize: 18
                },
                {
                    text: "Calle Ignacio Carrera Pinto, N°628 Quintero, V región",
                    fontSize: 10
                }
            ]
        ]
    };

    // si es 0, es de ingreso
    if (tipo == 0)
    {
        var imagen = "data:image/jpeg;base64,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";
        var n = comprobante.numero;
        for (var i=0; i<=Math.max(0,8-comprobante.numero.length); i++) {
            n = "0"+n;
        }

        var p = (100/12)+"%";

        // definicion del documento
        var docDefinition = { 
            pageMargins: [20, 80, 20, 40],
            header: {
                margin: 20,
                columns: [
                    {
                        image: imagen,
                        width: 50
                    },
                    [
                        {
                            text: "Agrupación MANUTEX",
                            fontSize: 18,
                            margin: [10, 15, 0, 0]
                        },
                        {
                            text: "Calle Ignacio Carrera Pinto, N°628 Quintero, V región",
                            fontSize: 10,
                            margin: [10, 0, 0, 0]
                        }
                    ],
                    [
                        {
                            text: [
                                {text: "Fecha : ",bold:true},
                                comprobante.fecha
                            ],
                            margin: [130, 16, 0, 0]
                        },
                        {
                            text: [
                                {text: "Código : ",bold:true},
                                n
                            ],
                            margin: [130, 0, 0, 0]
                        }
                    ]
                ]
            },
            content: [
                {
                    text: comprobante.titulo,
                    style: "header",
                    alignment: "center",
                    margin: [0,10,0,0]
                },
                {
                    table: {
                        widths: ['20%','80%'],
                        body: [
                            [{text:"Nombre",bold:true,fillColor:"#DBDBDB"},comprobante.nombre],
                            [{text:"RUT",bold:true,fillColor:"#DBDBDB"},comprobante.rut]
                        ]
                    },
                    margin: [0,10,0,0]
                },
                {
                    table: {
                        widths: ['20%','80%'],
                        body: [
                            [
                                {text:"Cuota Mensual ($)",bold:true,fillColor:"#DBDBDB"},
                                {text:"1.000",alignment:"right"}
                            ],
                            [
                                {text:"Monto ($)",bold:true,fillColor:"#DBDBDB"},
                                {text:comprobante.monto,alignment:"right"}
                            ],
                            [
                                {
                                    text: "Equivalente al pago de "+comprobante.cantidad+" cuota(s)",
                                    colSpan: 2,
                                    alignment: "right",
                                    italics: true,
                                    fillColor:"#DBDBDB"
                                }
                            ]
                        ]
                    },
                    margin: [0,10,0,0]
                },
                {
                    table: {
                        widths: ["100%"],
                        body: [
                            [
                                {
                                    text: "Año 2016",
                                    alignment: "center",
                                    fillColor:"#DBDBDB",
                                    bold: true
                                }
                            ]
                        ]
                    },
                    margin: [0,10,0,0]
                },
                {
                    table: {
                        widths: [p,p,p,p,p,p,p,p,p,p,p,p],
                        body: [
                            [
                                {
                                    text: "ENE",
                                    alignment: "center",
                                    fillColor:"#DBDBDB",
                                    bold:true
                                },
                                {
                                    text: "FEB",
                                    alignment: "center",
                                    fillColor:"#DBDBDB",
                                    bold:true
                                },
                                {
                                    text: "MAR",
                                    alignment: "center",
                                    fillColor:"#DBDBDB",
                                    bold:true
                                },
                                {
                                    text: "ABR",
                                    alignment: "center",
                                    fillColor:"#DBDBDB",
                                    bold:true
                                },
                                {
                                    text: "MAY",
                                    alignment: "center",
                                    fillColor:"#DBDBDB",
                                    bold:true
                                },
                                {
                                    text: "JUN",
                                    alignment: "center",
                                    fillColor:"#DBDBDB",
                                    bold:true
                                },
                                {
                                    text: "JUL",
                                    alignment: "center",
                                    fillColor:"#DBDBDB",
                                    bold:true
                                },
                                {
                                    text: "AGO",
                                    alignment: "center",
                                    fillColor:"#DBDBDB",
                                    bold:true
                                },
                                {
                                    text: "SEP",
                                    alignment: "center",
                                    fillColor:"#DBDBDB",
                                    bold:true
                                },
                                {
                                    text: "OCT",
                                    alignment: "center",
                                    fillColor:"#DBDBDB",
                                    bold:true
                                },
                                {
                                    text: "NOV",
                                    alignment: "center",
                                    fillColor:"#DBDBDB",
                                    bold:true
                                },
                                {
                                    text: "DIC",
                                    alignment: "center",
                                    fillColor:"#DBDBDB",
                                    bold:true
                                }
                            ],
                            [
                                {
                                    text: "00/0000",
                                    alignment: "center"
                                },
                                {
                                    text: "00/0000",
                                    alignment: "center"
                                },
                                {
                                    text: "*",
                                    alignment: "center"
                                },
                                {
                                    text: "*",
                                    alignment: "center"
                                },
                                {
                                    text: "*",
                                    alignment: "center"
                                },
                                {
                                    text: "*",
                                    alignment: "center"
                                },
                                {
                                    text: "*",
                                    alignment: "center"
                                },
                                {
                                    text: "*",
                                    alignment: "center"
                                },
                                {
                                    text: "*",
                                    alignment: "center"
                                },
                                {
                                    text: "*",
                                    alignment: "center"
                                },
                                {
                                    text: "*",
                                    alignment: "center"
                                },
                                {
                                    text: "*",
                                    alignment: "center"
                                }
                            ],
                            [
                                {
                                    text: "(*) corresponde al pago de cuotas que se esta realizando actualmente",
                                    colSpan: 12,
                                    border: [false,true,false,false]
                                }
                            ]
                        ]
                    },
                    layout: {
                        paddingLeft: function(i, node) { return 0; },
                        paddingRight: function(i, node) { return 0; }
                    },
                    margin: [0,-1,0,0],
                    fontSize: 10
                }
            ],
            footer: {
                margin: [40, 0, 40, 0],
                table: {
                    widths: ["25%","50%","25%"],
                    body: [[
                        {
                            border:[false,true,false,false],
                            text:"Firma Tesorera",
                            alignment: "center"
                        },
                        {
                            border:[false,false,false,false],
                            text:""
                        },
                        {
                            border:[false,true,false,false],
                            text:"Firma Socia",
                            alignment: "center"
                        }
                    ]]
                },
                layout: {
                    defaultBorder: false
                }
            },
            styles: {
                header: {
                    fontSize: 18,
                    bold: true,
                    alignment: 'justify'
                }
            }
        };

        // open the PDF in a new window
        pdfMake.createPdf(docDefinition).open();
    }
    // si es 1, es de pago de cuota
    else if (tipo == 1)
    {

    }
}

// IR AL MANTENEDOR DE SOCIAS
$('.socia_mant').click(function(){

    // se hace una peticion ajax para cargar la tabla
    $.ajax({
        type: "POST",
        url: url_global+"Socia/listar",
        data: {
            permiso: login_pass,
            estado: 1,
            llave: true
        }
    }).done(function(r1) {
        $.ajax({
            type: "POST",
            url: url_global+"Socia/listar",
            data: {
                permiso: login_pass,
                estado: 3,
                llave: true
            }
        }).done(function(r2) {
            vista_actual = "Socia";
            $('#principal_vista').load(
                url_global+"Principal/cargar/Socia",
                {
                    login_pass: login_pass,
                    tabla: r1,
                    eliminadas: r2,
                    llave: true
                }
            );
        });  
    });
});

// IR A GESTIONAR PERMISOS
$('.permisos').click(function(){
    $.ajax({
        type: "POST",
        url: url_global+"Socia/lista_permisos",
        data: {
            usuario_permiso: login_pass,
            llave: true
        }
    }).done(function(response) {
        vista_actual = "Permisos";
        $('#principal_vista').load(
            url_global+"Principal/cargar/Permisos",
            {
                login_pass: login_pass,
                tabla: response,
                llave: true
            }
        );
    });
});

// IR AL MANTENEDOR DE EVENTOS
$('.evento_mant').click(function(){
    $.ajax({
        type: "POST",
        url: url_global+"Evento/listar",
        data: {
            llave: true
        }
    }).done(function(r1) {
        vista_actual = "Evento";
        $('#principal_vista').load(
            url_global+"Principal/cargar/Evento",
            {
                tabla: r1,
                llave: true
            }
        );
    });
});

// IR A CREAR ASISTENCIA
$('.asis_mant').click(function(){
    $.ajax({
        type: "POST",
        url: url_global+"Socia/lista_consultar",
        data: {
            llave: true
        }
    }).done(function(response1){
        $.ajax({
            type: "POST",
            url: url_global+"Evento/consultar_eventos",
            data: {
                estado: 1,
                llave: true
            }
        }).done(function(response2){
            vista_actual = "Asistencia";
            $('#principal_vista').load(
                url_global+"Principal/cargar/Asistencia",
                {
                    tabla: response1,
                    eventos: response2,
                    llave: true
                }
            );
        });
    });
});

// IR A CONSULTAR ASISTENCIA
$('.asistencia').click(function(){
    var año = moment(new Date()).format("YYYY");
    $.ajax({
        type: "POST",
        url: url_global+"Evento/consultar_eventos_socia",
        data: {
            rut: login_rut,
            año: año,
            llave: true
        }
    }).done(function(response) {
        $.ajax({
            type: "POST",
            url: url_global+"Socia/obtener_nombre",
            data: {
                login_rut: login_rut,
                llave: true
            }
        }).done(function(response2){
            vista_actual = "Asistencia";
            $('#principal_vista').load(
                url_global+"Principal/cargar/ConsultarAsistencia",
                {
                    tabla: response,
                    nombre: response2,
                    año: año,
                    llave: true
                }
            );
        });
    });
});

// IR AL MANTENEDOR DE TIPOS DE MÁQUINAS
$('.tipos_mant').click(function(){
    $.ajax({
        type: "POST",
        url: url_global+"TipoMaquina/listar",
        data: {
            llave: true
        }
    }).done(function(response) {
        vista_actual = "TipoMaquina";
        $('#principal_vista').load(
            url_global+"Principal/cargar/Tipo",
            {
                login_pass: login_pass,
                tabla: response,
                llave: true
            }
        );
    });
});

// IR AL MANTENEDOR DE MÁQUINAS
$('.maquina_mant').click(function(){
    $.ajax({
        type: "POST",
        url: url_global+"Maquina/listar",
        data: {
            llave: true
        }
    }).done(function(response1) {
        $.ajax({
            type: "POST",
            url: url_global+"TipoMaquina/listar_tipos",
            data: {
                llave: true
            }
        }).done(function(response2) {
            vista_actual = "Maquina";
            $('#principal_vista').load(
                url_global+"Principal/cargar/Maquina",
                {
                    login_pass: login_pass,
                    tabla: response1,
                    tipos: response2,
                    llave: true
                }
            );
        });
    });
});

// IR A VER NOTIFICACIONES
$('.notif_ver').click(function(){
    // se carga la pagina enviandole la tabla de socias a la sub vista
    $.ajax({
        type:"POST",
        url:url_global+"Notificacion/consultar_notificaciones",
        data:{
            rut: login_rut,
            llave: true
        }
    }).done(function(response){
        vista_actual = "Notificacion";
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

// IR A MODIFICAR PERFIL
$('.perfil_mant').click(function(){
    $.ajax({
        type: "POST",
        url: url_global+"Socia/obtener_perfil",
        data: {
            login_rut: login_rut,
            llave: true
        }
    }).done(function(response) {
        vista_actual = "Perfil";
        $('#principal_vista').load(
            url_global+"Principal/cargar/Perfil",
            {
                tabla: response,
                llave: true
            }
        );
    });
});

// IR A SOLICITAR MÁQUINAS
$('.sol_maq').click(function(){
    $.ajax({
        type: "POST",
        url: url_global+"Maquina/consultar_solicitudes",
        data: {
            rut: login_rut,
            llave: true
        }
    }).done(function(response){
        vista_actual = "Maquina";
        $('#principal_vista').load(
            url_global+"Principal/cargar/Solicitar",
            {
                tabla: response,
                llave: true
            }
        );
    });
});

// IR A INGRESAR TRANSACCIONES
$('.trans_mant').click(function(){
    $.ajax({
        type: "POST",
        url: url_global+"Transaccion/listar",
        data: {
            llave: true
        }
    }).done(function(response1) {
        $.ajax({
            type: "POST",
            url: url_global+"Transaccion/ultima",
            data: {
                llave: true
            }
        }).done(function(response2){
            vista_actual = "Transaccion";
            $('#principal_vista').load(
                url_global+"Principal/cargar/Transaccion",
                {
                    tabla: response1,
                    ultima: response2,
                    llave: true
                }
            );
        });
    });
});    

// IR A ACEPTAR SOLICITUDES
$(".sol_aceptar").click(function(){
    $.ajax({
        type: "POST",
        url: url_global+"Maquina/listar_aceptar",
        data: {
            llave: true
        }
    }).done(function(response) {
        vista_actual = "Maquina";
        $('#principal_vista').load(
            url_global+"Principal/cargar/AceptarSolicitud",{
                tabla: response,
                llave: true
            }
        );
    });
});

// RESPALDAR LOS DATOS
$(".respaldar").click(function(){
    $("#respaldo-mensaje").text("");
    $(".respaldo-mensaje-def").show();
    $(".respaldo-mensaje").hide();
    $("#respaldo-modal .modal-footer button").show();
    $("#respaldo-modal .modal-footer button.salir").text("Cancelar");
    $("#respaldo-modal").modal("show");
});
$("#respaldo-enviar").click(function(){
    $(".respaldo-mensaje-def").hide();
    $(".respaldo-mensaje").show();
    $(".respaldo-mensaje .loader").show();
    $(".respaldo-mensaje span").text("Generando y subiendo el archivo, espere un momento...");
    $("#respaldo-modal").css("pointer-events", "none");
    $("#respaldo-modal .modal-footer button").hide();
    $.ajax({
        type: "POST",
        url: url_global+"Principal/respaldar",
        data:{
            respaldar : true,
            llave: true
        }
    }).done(function(response){
        if (response == 1) {
            $(".respaldo-mensaje .loader").hide();
            $(".respaldo-mensaje span").text("El respaldo se realizo exitosamente");
            $("#respaldo-modal .modal-footer button").show();
            $("#respaldo-enviar").hide();
            $("#respaldo-modal .modal-footer button.salir").text("Cerrar");
            $("#respaldo-modal").css("pointer-events", "auto");
        }
    });
});

//cuadrar la caja
$('.cuadrar').click(function(){
    var año = moment(new Date()).format("YYYY");
    $.ajax({
        type: "POST",
        url: url_global+"Transaccion/ingresos",
        data:{
            año : año,
            llave: true
        }
    }).done(function(response){
        $.ajax({
            type:"POST",
            url: url_global+"Transaccion/egresos",
            data:{
                año : año,
                llave: true
            }
        }).done(function(response1){
            $.ajax({
                type:"POST",
                url: url_global+"Transaccion/Cuadrar_caja",
                data:{
                    año : año,
                    llave: true
                }
            }).done(function(response2){
                vista_actual = "Transaccion";
                $('#principal_vista').load(
                url_global+"Principal/cargar/Cuadrarcaja",
                {
                    tablaingreso: response,
                    tablaegreso:response1,
                    año : año,
                    aa : response2,
                    llave: true
                }
            ); 
            });
        });
       }); 
    });


//ingresar cuotas
$('.ingresar_cuotas').click(function(){
    var año = moment(new Date()).format("YYYY");
    $.ajax({
        type: "POST",
        url: url_global+"Transaccion/consultar_valor_anual",
        data:{
            año : año,
            llave: true
        }
    }).done(function(response){
        $.ajax({
            type: "POST",
            url: url_global+"Transaccion/listar_cuotas",
            data:{
                año : año,
                llave: true
            }
        }).done(function(response1){
            vista_actual = "Transaccion";
            $('#principal_vista').load(
            url_global+"Principal/cargar/Ingresarcuotas",
            {
                año : año,
                tabla:response1,
                valores:response,
                llave: true
            }
            );
        });    
    });
});

//revisar deudas
$('.cuotas').click(function(){
    var año = moment(new Date()).format("YYYY");
    $.ajax({
        type: "POST",
        url: url_global+"Transaccion/consultar_pagos",
        data: { 
            año : año,
            rut: login_rut,
            llave: true
        }
    }).done(function(response){
        $.ajax({
            type:"POST",
            url: url_global+"Transaccion/Cuadrar_caja",
            data:{
                año:año,
                llave: true
            }
        }).done(function(response1){
            $.ajax({
                type:"POST",
                url: url_global+"Transaccion/consultar_valor_anual",
                data:{
                    año:año,
                    llave: true
                }  
            }).done(function(response2){
                vista_actual = "Transaccion";
                $('#principal_vista').load(
                    url_global+"Principal/cargar/Cuotas",
                    {
                        año : año,
                        cuotas:response,
                        caja: response1,
                        valor: response2,
                        llave: true
                    }
            );
            });
        });
        
    });  
});

// cerrar sesion
$("#principal_cerrar a").click(function(event){
    event.preventDefault();
    $.ajax({
        type: "POST",
        url: url_global+"Principal/salir",
        data: {
            login_rut: login_rut,
            llave: true
        }
    }).done(function(response){
        window.location = event.target;
    });
});

// $("body").on("keypress",function(e){
//     if (e.key == 'p') {
//         var comprobante = {
//             tipo: 0,
//             fecha: "00/00/0000",
//             numero: "1",
//             titulo: "Comprobante pago de cuotas",
//             nombre: "Fabián Ignacio Pulgar López",
//             rut: "18.563.134-6",
//             monto: "10.000",
//             cantidad: "10"
//         };
//         generar_comprobante(comprobante);
//     }
// });