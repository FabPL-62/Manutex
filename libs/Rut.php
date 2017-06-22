<?php

// para obtener el digito verificador, a traves del cuerpo del rut
function digito_verificador($rut)
{
    $suma   = 0;
    $factor = 2;
    $posicion = strlen($rut)-1;
    while ($posicion != -1){
        $digito = substr($rut, $posicion, 1);
        $suma = $suma + $factor*$digito;
        $factor = $factor + 1;
        $posicion = $posicion - 1;
        if ($factor == 8) $factor = 2;
    }
    $diferencia = 11 - $suma%11;
    switch ($diferencia)
    {
        case 11 : return "0"; break;
        case 10 : return "K"; break;
        default : return $diferencia; 
    }
}

// verificar que el rut este bien escrito
function rut_verificar($rut)
{
    // el rut no debe ser nulo
    if (strlen($rut) > 0)
    {
        // primero separamos el rut en rut - dv
        $rut = explode("-",$rut);

        // verificamos que $rut sea un arreglo de 2 elementos
        if (count($rut) == 2)
        {
            // el digito verificador se hace en mayusculas
            $rut[1] = strtoupper($rut[1]);

            // verificamos que el rut sea numerico
            if (is_numeric($rut[0]))
            {
                // el rut debe ser un numero con mas de 7 digitos
                if (strlen($rut[0]) >= 7)
                {
                    // ahora verificamos que el digito verificador sea un numero o K
                    if (is_numeric($rut[1]) or $rut[1] == "K")
                    {
                        // vemos si el rut concuerda con su digito verificador
                        if (digito_verificador($rut[0]) == $rut[1])
                        {
                            return $rut[0];
                        }
                        else return "Rut y dígito verificador no coinciden";
                    }
                    else return "Dígito verificador inválido";
                }
                else return "El rut debe tener entre 7 a 8 dígitos";
            }
            else return "El rut debe ser numérico";
        } 
        else return "Formato de rut inválido";
    }
    else return "Ingrese el rut";
}