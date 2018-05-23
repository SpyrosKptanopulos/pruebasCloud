<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.mypurecloud.com/api/v2/outbound/contactlists",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\r\n   \"name\": \"PruebaCargaJil\",\r\n   \"version\": 0,\r\n   \"columnNames\": [\"NOMBRE\",\"BANCO\",\"MONTO\",\"FECHAHORATRANSACCION\",\"TELEFONO\",\"RUT\",\"LUGARTRANSACCION\"],\r\n      \"phoneColumns\": [\r\n      {\r\n         \"columnName\": \"TELEFONO\",\r\n         \"type\": \"Movil\",\r\n         \"callableTimeColumn\": \"\"\r\n      }\r\n   ],\r\n   \"previewModeColumnName\": \"\",\r\n   \"previewModeAcceptedValues\": [],\r\n   \"attemptLimits\": {\r\n      \"id\": \"\",\r\n      \"name\": \"\",\r\n      \"selfUri\": \"\"\r\n   }\r\n}",
  CURLOPT_HTTPHEADER => array(
    "Authorization: bearer lnaN9Mu7Hwz-lR-q4XXbeys6PxtnM0smLEXkbz_s18jpVHmu5ViBXyRZlBaYB44XTFq7WSMCPRrQ7yMjcKV9sQ",
    "Cache-Control: no-cache",
    "Content-Type: application/json",
    "Postman-Token: ea7e012d-3d93-4af0-8fbd-c7c72e0c3b6a"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}