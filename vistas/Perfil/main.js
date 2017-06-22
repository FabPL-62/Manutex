// validar el email
function validar_email(email) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}

$("#ant").on("keyup",function(){
	var val = $(this).val();
	if (val != "") {
		if (val == pass) {
			if ($(this).parents(".form-group").hasClass("has-success") == false) {
				$(this).parents(".form-group").removeClass("has-error");
				$(this).parents(".form-group").addClass("has-success");
	            $(this).css({"background-color":"#B5EBAA"});
	            $("#add-ant").html("<span class='glyphicon glyphicon-ok'></span>");
        	}
		} else {
			if ($(this).parents(".form-group").hasClass("has-error") == false) {
				$(this).parents(".form-group").removeClass("has-success");
				$(this).parents(".form-group").addClass("has-error");
	            $(this).css({"background-color":"#FDCDCD"});
	            $("#add-ant").html("<span class='glyphicon glyphicon-remove'></span>");
        	}
		}
	} else {
		$(this).parents(".form-group").removeClass("has-success");
		$(this).parents(".form-group").removeClass("has-error");
    	$(this).css({"background-color":""});
    	$("#add-ant").html("");
	}
});

// se valida el cambio de contraseña
$("#nueva2,#nueva").on("keyup",function(){
	var nueva2 = $("#nueva2").val();
	var nueva = $("#nueva").val();
	if (nueva2 != "") {
		if (nueva2 == nueva) {
			if ($("#nueva2,#nueva").parents(".form-group").hasClass("has-success") == false) {
				$("#nueva2,#nueva").parents(".form-group").removeClass("has-error");
				$("#nueva2,#nueva").parents(".form-group").addClass("has-success");
	            $("#nueva2,#nueva").css({"background-color":"#B5EBAA"});
	            $("#add-nueva2,#add-nueva").html("<span class='glyphicon glyphicon-ok'></span>");
        	}
		} else {
			if ($("#nueva2,#nueva").parents(".form-group").hasClass("has-error") == false) {
				$("#nueva2,#nueva").parents(".form-group").removeClass("has-success");
				$("#nueva2,#nueva").parents(".form-group").addClass("has-error");
	            $("#nueva2,#nueva").css({"background-color":"#FDCDCD"});
	            $("#add-nueva2,#add-nueva").html("<span class='glyphicon glyphicon-remove'></span>");
        	}
		}
	} else {
		$("#nueva2,#nueva").parents(".form-group").removeClass("has-success");
		$("#nueva2,#nueva").parents(".form-group").removeClass("has-error");
    	$("#nueva2,#nueva").css({"background-color":""});
    	$("#add-nueva2,#add-nueva").html("");
	}
});

// confirmar los cambios
$("#Guardar").click(function(){
	
	// limpiamos si hay errores
	$("#datos_perfil .form-group").removeClass("has-error");
    $("#datos_perfil input").css({"background-color":""});

	// validamos que los campos requeridos no esten vacios
	var mi_fono = $("#fono").val();
	var mi_direccion = $("#dir").val();
	var mi_email = $("#email").val();

	var mi_error = false;

	if (mi_fono == "") {
		$("#fono").parents(".form-group").addClass("has-error");
		$("#fono").css({"background-color":"#FDCDCD"});
		$("#vista_footer p").html("Error : complete los campos requeridos <b>(*)</b>");
		$("#fono").focus();
		mi_error = true;
	}
	if (mi_direccion == "") {
		$("#dir").parents(".form-group").addClass("has-error");
		$("#dir").css({"background-color":"#FDCDCD"});
		if (mi_error == false) {
			$("#vista_footer p").html("Error : complete los campos requeridos <b>(*)</b>");
			$("#dir").focus();
			mi_error = true;
		}
		
	}
	if (mi_email != "") {
		if (validar_email(mi_email) == false) {
			$("#email").parents(".form-group").addClass("has-error");
			$("#email").css({"background-color":"#FDCDCD"});
			if (mi_error == false) {
				$("#vista_footer p").html("Error : Email con formato inválido");
				$("#email").focus();
				mi_error = true;
			}
		}
	} 

	// si no hay errores
	if (mi_error == false)
	{
		// se analizan las contraseñas
		var ant = $("#ant").val();
		var nueva = $("#nueva").val();
		var nueva2 = $("#nueva2").val();

		// si se ha escrito algo en ant
		if (ant != "") {
			if (ant == pass) {
				if (nueva != "") {
					if (nueva2 != "") {
						if (nueva != nueva2) {
							$("#vista_footer p").text("Las nuevas contraseñas no coinciden");
							$("#nueva2").focus();
							mi_error = true;
						}
					} else {
						$("#vista_footer p").text("Debe ingresar la nueva contraseña otra vez");
						$("#nueva2").focus();
						mi_error = true;
					}
				} else {
					$("#vista_footer p").text("Debe ingresar la nueva contraseña");
					$("#nueva").focus();
					mi_error = true;
				}
			} else {
				$("#vista_footer p").text("La antigua contraseña no coincide");
				$("#ant").focus();
				mi_error = true;
			}
		} else {
			if (nueva != "" || nueva2 != "") {
				$("#vista_footer p").text("Debe ingresar la atigua contraseña");
				$("#ant").focus();
				mi_error = true;
			} 
		}
	}

	// si no hay errores
	if (mi_error == false)
	{
		// se obtiene la nueva contraseña
		// si es vacia, se ignora la edicion
		var mi_pass = $("#nueva").val();

		// se hace la peticion para actualizar
		$.ajax({
			type: "POST",
			url: url_global+"Socia/modificar_perfil",
			data: {
				socia_rut: login_rut,
				socia_fono: mi_fono,
				socia_direccion: mi_direccion,
				socia_email: mi_email,
				socia_contraseña: mi_pass,
				llave: true
			}
		}).done(function(response){
			var mensaje = JSON.parse(response);
			if (mensaje[0] == 1) {
				$("#vista_footer p").text(mensaje[1]);
			} else {
				mensaje_error(mensaje[1]);
			}
		});
	}
});