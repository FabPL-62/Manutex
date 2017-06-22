// si se presiona el teclado en el input #login_rut
$('#login_rut').keypress(function(e){

    // se obtiene el contenido del input
    contenido = $(this).val();

    // se obtiene el ultimo caracter del contenido
    ultimo = contenido.charAt(contenido.length-1);

    // si alguno se cumple, ignora la nueva entrada
    if ((e.key < '0' || e.key > '9') 
    && ((e.key != "K" && e.key != "k") || ultimo != "-") 
    && (e.key != "-" || cuenta(contenido,"-") != 0 || contenido.length <= 6)
    && e.charCode != 0)
        return false;
});

// si se presiona el boton submit
$('#login_button').click(function(e){

    // se ignoran los eventos por defecto
    e.preventDefault();

    // se llama a la funcion login
    login();

});

function padding_login() {
    var h1 = $(".login-clean").height();
    var h2 = $("#form_login").height()+160;
    var r = Math.round(Math.max(0,(h1 - h2)/2));
    $("#form_login").css({
        "margin-top" : r+"px"
    });
}

// cuando se redimensiona la ventana del navegador
padding_login();
$(window).resize(function(){
    padding_login();
});

// funcion para loguearse
function login() {

    // se obtiene el rut y el password
    var login_rut = $('#login_rut')[0].value;
    var login_pass = $('#login_pass')[0].value;

    // peticion ajax hacia el metodo login del controlador Login
    $.ajax({
        type: "POST",
        url: url_global+"Login/login",
        data: {
            login_rut: login_rut, 
            login_pass: login_pass,
            llave: true
        }
    }).done(function(response){
        alert(response);

        // se decodifica la respuesta
        var mensaje = JSON.parse(response);
        if (mensaje[0] == 1) {

            // se llenan las casillas del formulario para enviar a la otra vista
            valores = mensaje[1];
            $('#login_pass').css({'color':'white'});
            $('#login_nombre').val(valores[0]);
            $('#login_pass').val(valores[1]);
            $('#form_login').submit();

        } else {
            // se muestra un mensaje de error
            $('.mensaje').html("Error : "+mensaje[1]);
        }
        
    });
}