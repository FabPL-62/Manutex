 
 var año_ant = año_actual;

 var tabla;
$(document).ready(function(){
    
    // se carga la forma como datatable
    tabla = $('table.display').DataTable({
        /*"scrollY": alto_tabla(),*/
        "scrollX": true,
        "paging": false,
        "searching": false,
        "info": false,
        "language": {
            "emptyTable": "No hay datos disponibles"
        }, 
         "columnDefs": [ 
            {
              "render": function ( data, type, row ) {
                  return '$'+ data;
                },
                "targets": 2
            }
        ],
        "createdRow": function ( row, data, index ) {
            if ( data[2] < 0) {
                $(row).css({"background-color":"#FDCDCD"}).addClass('highlight');
            }
        }
    });
 });   



// el ingreso del año solo acepta numeros
$("#año").keypress(function(e){
    return false;
});

// cuando cambia el año, se restringe que salga de un rango
$("#año").on("input",function(){

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
    $("#año").val(nuevo);
});

/*// si se presiona confirmar
$("#año-confirmar").on("click",function(){
    var año = $("#año").val();
    if (año != "") {

        // si el año es diferente al anterior
        if (año != año_ant) {
            $.ajax({
                type: "POST",
                url: url_global+"Transaccion/ingresos",
                data:{
                    año : año
                }
            }).done(function(response){
                $.ajax({
                    type:"POST",
                    url: url_global+"Transaccion/egresos",
                    data:{
                        año : año
                    }
                }).done(function(response1){
                    $.ajax({
                        type:"POST",
                        url: url_global+"Transaccion/Cuadrar_caja",
                        data:{
                            año : año
                        }
                    }).done(function(response2){
                        alert(response);
                        alert(response1);
                        alert(response2);
                      tabla.clear();
                      tabla_ingreso=JSON.parse(response);
                      tabla_egreso=JSON.parse(response1); 
                      $("#tabla_ingreso").data(tabla_ingreso);
                      tabla.draw();   
                      var datos = JSON.parse(response2);
                      if (datos[0][0] != null){
                        $("#ingresos").text("Ingresos = $"+datos[0][0]);
                        $("#egresos").text("Egresos = $"+datos[0][1]);
                        $("#total").text("Total en caja = $"+datos[0][2]);
                      }else{
                        $("#ingresos").text("Ingresos = $0");
                        $("#egresos").text("Egresos = $0");
                        $("#total").text("Total en caja = $0");
                      }
                     
            });
        });
       });
        }
    }    
});*/
