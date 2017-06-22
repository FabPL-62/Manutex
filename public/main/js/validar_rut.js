function rut_dv(rut_par) 
{
	posicion = rut_par.length - 1;
	factor = 2;
	suma = 0;
	while (posicion != -1)
	{
		digito = parseInt(rut_par.charAt(posicion));
		suma += factor*digito;
		factor += 1;
		posicion -= 1;
		if (factor == 8) factor = 2;
	}
	diferencia = 11 - suma%11;
	switch (diferencia) {
		case 11 : 
			return "0"; 
			break;
		case 10 : 
			return "K"; 
			break;
		default : 
			return diferencia.toString();
	}
}

function rut_validar(rut_par)
{
	// el rut no debe ser nulo
	if (rut_par.length > 0)
	{
		// primero separamos el rut en rut - dv
		rut_arreglo = rut_par.split("-");

		// verificamos que $rut sea un arreglo de 2 elementos
		if (rut_arreglo.length == 2)
		{
			// el digito verificador se hace en mayusculas
			var dv = rut_arreglo[1].toUpperCase();

			// verificamos que el rut sea numerico
			if (!isNaN(rut_arreglo[0]))
			{
				// el rut debe ser un numero con mas de 7 digitos
				if (rut_arreglo[0].length >= 7)
				{
					// verificamos que haya un digito verificador
					if (dv.length > 0)
					{
						// ahora verificamos que el digito verificador sea un numero o K
						if (!isNaN(dv) || dv == "K")
						{
							// vemos si el rut concuerda con su digito verificador
							if (rut_dv(rut_arreglo[0]) == dv){
								return true;
							} else return "El rut y el digito verificador no coinciden";
						} else return "El dígito verificador es inválido";
					} else return "Debe ingresar el dígito verificador";
				} else return "El rut no puede ser inferior a 7 dígitos";
			} else return "Formato de rut inválido";
		} else return "Formato de rut inválido";
	} else return "Ingrese el rut";
}

function cuenta(str,coincidencia) {  
	var regex = new RegExp("[^"+ coincidencia +"]","g");
	str = str.replace(regex, "").length;
	return str;
}