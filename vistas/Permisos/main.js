// obtener alto para la tabla
function alto_tabla() {
    return $("#vista_contenido").height()-50;
}

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
        "info": false
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

// confirmar los cambios
$("#Confirmar").click(function(){
    mensaje_confirmar("Confirmar modificación","Esta segura de realizar la modificación?",
    function(){
        var rut_permisos = [];
        $("#tabla tbody tr").each(function(i,v){
            var mi_rut, mi_permiso;
            $(this).find('td').each(function(ii,vv){
                if (ii == 0) {
                    mi_rut = $(this).text().split("-")[0];
                }
                if (ii == 4) {
                    mi_permiso = $(this).find(".permiso option:selected").val();
                }
            });
            rut_permisos.push([mi_rut,mi_permiso]);
        });
        var pc = 0;
        for (var i in rut_permisos) {
            var reg = rut_permisos[i];
            var per = reg[1];
            if (per == "0") pc++;
        }
        if (pc > 0) {
            var permisos_data = JSON.stringify(rut_permisos);
            $.ajax({
                type: "POST",
                url: url_global+"Socia/gestionar_permisos",
                data: {
                    tabla_permisos: permisos_data,
                    llave: true
                }
            }).done(function(response){
                $("#vista_footer p").text(response);
            }); 
        } else {
            mensaje_error("Debe haber al menos una Presidenta");
            $("#vista_footer p").text("No se realizaron cambios");
        }
    },function(){
        $("#vista_footer p").text("Se canceló la operación de modificación");
    });
    	
});