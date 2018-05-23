<?php

$pizza = '"NOMBRE","BANCO","MONTO","FECHAHORATRANSACCION","TELEFONO","RUT","LUGARTRANSACCION"';
$porciones = explode(",", $pizza);
$count = 0;
$listPhone = "";
foreach ($porciones as $key => $value) {
    //echo $value . "\n";
    if (strpos($value, "TELE")) {
        //      echo $value. "\n";
        $listPhone = $listPhone . " {\r\n
            \"columnName\": \"" . $value . "\",\r\n
            \"type\": \"Movil" . $count . "\",\r\n
            \"callableTimeColumn\": \"\"\r\n
        },\r\n";
    }
    $count++;
}
//echo $listPhone;

$rest = substr($listPhone, 0, -6);
echo $rest."}\r\n";