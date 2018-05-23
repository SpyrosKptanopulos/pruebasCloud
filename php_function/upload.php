<?php
include 'PureCloudClass.php';
$clientID = "6ffdb5d0-1ce1-4f47-8f31-137e761ab70b";
$secretID = "2K8JYGbUMa2_okrjfTEk8Pd91lDBtW_c7u86Y50GKLI";

$pureCloudClass = new PureCloudClass;

if ($_POST['Queue'] == '' || $_POST['modMarcado'] == '' || $_POST['nomCampaña'] == '') {
    echo "error";
} else {
    $idQueue = $_POST['Queue'];
    $modMarcado = $_POST['modMarcado'];
    $nomCampaña = $_POST['nomCampaña'];
    $iniciaCampaign = "";
    $statusContactList = "";
    $idContactList = "";
    $statusCampaign = "";
    $idCampaign = "";

    if (isset($_POST['onoffswitch'])) {
        $iniciaCampaign = $_POST['onoffswitch'];
    } else {
        $iniciaCampaign = 'off';
    }

    $target_dir = "";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

    $token = $pureCloudClass->getToken($clientID, $secretID);

    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {

        $file = $_FILES["fileToUpload"]["name"];
        $data = "";

        if (($fichero2 = fopen($file, "r")) !== false) {
            $path_parts = pathinfo($file);
            $nombreArchivo = $path_parts['filename'];
            $nombres_campos = fgetcsv($fichero2, 0, ",", "\"", "\"");
            $num_campos = count($nombres_campos);

            $campos = '"' . implode('","', $nombres_campos) . '"';
            fclose($fichero2);
        }

        $porciones = explode(",", $campos);
        $count = 0;
        $listPhone = "";
        foreach ($porciones as $key => $value) {
            if (strpos($value, "TELE")) {
                $listPhone = $listPhone . " {\r\n
            \"columnName\":  $value ,\r\n
            \"type\": \"Movil" . $count . "\",\r\n
            \"callableTimeColumn\": \"\"\r\n
        },\r\n";
                $count++;
            }
        }

        $rest = substr($listPhone, 0, -6);
        $rest = $rest . "}\r\n";

        $contactListId = $pureCloudClass->createContactList($nombreArchivo, $campos, $rest, $token);

        //print_r($contactListId);

        $someObject = json_decode($contactListId);

        foreach ($someObject as $key => $val) {
            if ($key == "status") {
                $statusContactList = $val;
            } elseif ($key == "id") {
                $idContactList = $val;
            }
        }
        if ($statusContactList == '400') {
            echo "Creada";
        } else {
            $registros = array();
            if (($fichero = fopen($file, "r")) !== false) {
                // Lee los nombres de los campos
                $nombres_campos = fgetcsv($fichero, 0, ",", "\"", "\"");
                $num_campos = count($nombres_campos);
                // Lee los registros
                while (($datos = fgetcsv($fichero, 0, ",", "\"", "\"")) !== false) {
                    // Crea un array asociativo con los nombres y valores de los campos
                    for ($icampo = 0; $icampo < $num_campos; $icampo++) {
                        $registro[$nombres_campos[$icampo]] = $datos[$icampo];
                    }
                    // Añade el registro leido al array de registros
                    $registros[] = $registro;
                }
                fclose($fichero);
                unlink($file);
                for ($i = 0; $i < count($registros); $i++) {
                    for ($icampo = 0; $icampo < $num_campos; $icampo++) {
                        $data = $data . '"' . $nombres_campos[$icampo] . '": "'
                            . $registros[$i][$nombres_campos[$icampo]] . '",' . "\n";
                    }

                    $rest2 = substr($data, 0, -2);
                    $pureCloudClass->createContact($rest2, $idContactList, $token);
                }
                $createCam = $pureCloudClass->createCampaign($nomCampaña, $idContactList, $token, $rest, $modMarcado, $idQueue, $iniciaCampaign);

                //print_r($createCam);
                $someObject = json_decode($createCam);
                foreach ($someObject as $key => $val) {
                    if ($key == "status") {
                        $statusCampaign = $val;
                    } elseif ($key == "id") {
                        $idCampaign = $val;
                    }
                }
                if ($statusCampaign == '400') {
                    # code...
                    $pureCloudClass->deleteContactList($token, $idContactList);
                    echo 'create';
                } else {
                    # code...
                    echo "ok";
                }
            }
        }
    } else {
        echo "ErrorArchivo";
    }

}
