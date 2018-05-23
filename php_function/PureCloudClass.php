<?php

class PureCloudClass
{
    public function getToken($clientId, $clientSecret)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://login.mypurecloud.com/oauth/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "grant_type=client_credentials",
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json;odata=verbose",
                "Authorization: Basic " . base64_encode('' . $clientId . ':' . $clientSecret . ''),
                "Cache-Control: no-cache",
                "Content-Type: application/x-www-form-urlencoded",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            $someObject = json_decode($response);
            //print_r($someObject); // Dump all data of the Object
            foreach ($someObject as $key => $val) {
                if ($key == "access_token") {
                    # code...
                    return $val;
                }
            }

        }
    }

    public function getCallHistory()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.mypurecloud.com/api/v2/conversations/calls/history?pageSize=50&pageNumber=1",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer wbOAF79j8lxsjxYOm5Hpz2rPKrMegx3k79wRPvPqKOSH5RZkgQG9zMtk5IGezSPWvJ9utMh_0V-tsGQVHLUdeA",
                "Cache-Control: no-cache",
                "Postman-Token: 2a26362a-dc4a-486a-b267-bf340586eee7",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }

    public function getConversationData($token, $interval)
    {
        $count = 1;
        $response = "";
        $response2 = array();

        do {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.mypurecloud.com/api/v2/analytics/conversations/details/query",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "{\r\n \"interval\": \"" . $interval . "\",\r\n \"order\": \"asc\",\r\n \"orderBy\": \"conversationStart\",\r\n \"paging\": {\r\n  \"pageSize\": 100,\r\n  \"pageNumber\": " . $count . "\r\n }\r\n}",
                CURLOPT_HTTPHEADER => array(
                    "Authorization: bearer " . $token,
                    "Cache-Control: no-cache",
                    "Content-Type: application/json",
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);
            if ($err) {
                return "cURL Error #:" . $err;
            } else {
                //  return $response;
                //return array_push($array, $response);
                $task_array = json_decode($response);

                array_push($response2, $task_array);
            }
            $count++;
        } while ($response != '{}');

        return $response2;

    }

    public function getIntervalString()
    {
        $startTime = getdate();

        foreach ($startTime as $key => $value) {
            if ($key === "year") {
                $y = $value;
            } elseif ($key === "mon") {
                $m = $value;
                if (strlen($m) == 1) {
                    $m = "0" . $m;
                }
            } elseif ($key === "mday") {
                $d = $value;
                if (strlen($d) == 1) {
                    $d = "0" . $d;
                }
            }
        }
        $dT = date('d', strtotime("+1 days"));
        $mT = date('m', strtotime("+1 days"));

        $interval = $y . "-" . $m . "-" . $d . "T03:00:00.000Z/" . $y . "-" . $mT . "-" . $dT . "T03:00:00.000Z";

        return $interval;
    }

    public function getQueues($token)
    {
        $arrayQueue = "";
        $a = 1;
        $count = 1;
        $response = "";
        $response2 = array();

        do {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.mypurecloud.com/api/v2/routing/queues?pageSize=100&pageNumber=" . $a,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Authorization: bearer " . $token,
                    "Cache-Control: no-cache",
                    "Content-Type: application/json",
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                return "cURL Error #:" . $err;
            } else {
                //return $response;
                $task_array = json_decode($response);
                array_push($response2, $task_array);
                //return count($response2);
                for ($i = 0; $i < count($response2); $i++) {
                    # code...
                    if ($response2[$i]->entities == null) {
                        $arrayQueue = "";
                    }
                }
            }
            $a++;
        } while ($arrayQueue != "");
        return $response2;

    }

    public function getMembersQueue($idQueue, $token)
    {
        $arrayQueue = "";
        $a = 1;
        $response = "";
        $response2 = array();

        do {
            # code...
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.mypurecloud.com/api/v2/routing/queues/" . $idQueue . "/users?pageSize=100&pageNumber=" . $a . "&expand=routingStatus%2Cpresence",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Authorization: bearer " . $token,
                    "Cache-Control: no-cache",
                    "Content-Type: application/json",
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                return "cURL Error #:" . $err;
            } else {
                $task_array = json_decode($response);
                array_push($response2, $task_array);
                //return count($response2);
                for ($i = 0; $i < count($response2); $i++) {
                    # code...
                    if ($response2[$i]->entities == null) {
                        $arrayQueue = "";
                    }
                }
            }
            $a++;
        } while ($arrayQueue != "");
        return $response2;

    }

    public function getPrimaryPresenceDetails($token, $interval, $userID)
    {
        $arrayQueue = "";
        $a = 1;
        $response = "";
        $response2 = array();

        do {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.mypurecloud.com/api/v2/analytics/users/details/query",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "{\r\n \"interval\": \"" . $interval . "\",\r\n \"order\": \"asc\",\r\n \"paging\": {\r\n  \"pageSize\": \"100\",\r\n  \"pageNumber\": " . $a . "\r\n },\r\n \"userFilters\": [\r\n  {\r\n   \"type\": \"or\",\r\n   \"predicates\": [\r\n    {\r\n     \"type\": \"dimension\",\r\n     \"dimension\": \"userId\",\r\n     \"operator\": \"matches\",\r\n     \"value\": \"" . $userID . "\"\r\n    }\r\n   ]\r\n  }\r\n ]\r\n}",
                CURLOPT_HTTPHEADER => array(
                    "Authorization: bearer " . $token . "",
                    "Cache-Control: no-cache",
                    "Content-Type: application/json",
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                return "cURL Error #:" . $err;
            } else {
                if ($response != "{}") {
                    $task_array = json_decode($response);
                    array_push($response2, $task_array);
                    for ($i = 0; $i < count($response2); $i++) {
                        if ($response2[$i]->userDetails == null) {
                            $arrayQueue = "";
                        }
                    }

                } else {
                    $arrayQueue = "";
                    $response2 = "";
                }
            }
            $a++;
        } while ($arrayQueue != "");
        return $response2;
    }

    public function createContact($data, $contactListId, $token)
    {
        // return "\n";
        $body = "[{\n
        \"name\": \"\",\n
        \"contactListId\": \"" . $contactListId . "\",\n
        \"data\": {\n
                " . $data . "
            },\n
        \"callable\": true,\n
        \"phoneNumberStatus\": {}\n
        }]";

        // return $body;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.mypurecloud.com/api/v2/outbound/contactlists/" . $contactListId . "/contacts",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => array(
                "Authorization: bearer " . $token,
                "Content-Type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            //return "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }

    public function cargaCSV($archivo, $contactListId, $token)
    {
        $file = $archivo;
        $data = "";

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

            return "Leidos " . count($registros) . " registros\n";

            for ($i = 0; $i < count($registros); $i++) {
                for ($icampo = 0; $icampo < $num_campos; $icampo++) {
                    $data = $data . '"' . $nombres_campos[$icampo] . '": "'
                        . $registros[$i][$nombres_campos[$icampo]] . '",' . "\n";
                }

                $rest = substr($data, 0, -2);

                return $rest;
                return "\n";
                // return $data;
                return "\n";
                createContact($rest, $contactListId, $token);
            }
        }
    }

    public function createContactList($nombreContact, $campos, $telefonos, $token)
    {
        // return "\n";
        $body = "{\r\n
           \"name\": \"" . $nombreContact . "\",\r\n
           \"version\": 0,\r\n
           \"columnNames\": [" . $campos . "],\r\n
           \"phoneColumns\": [\r\n
                 " . $telefonos . "
               ],\r\n   \"previewModeColumnName\": \"\",\r\n   \"previewModeAcceptedValues\": [],\r\n   \"attemptLimits\": {\r\n      \"id\": \"\",\r\n      \"name\": \"\",\r\n      \"selfUri\": \"\"\r\n   }\r\n}";
        //return $body;
        //return "\n";
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.mypurecloud.com/api/v2/outbound/contactlists",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => array(
                "Authorization: bearer " . $token,
                "Cache-Control: no-cache",
                "Content-Type: application/json",
                "Postman-Token: 79ae4d45-a75c-4754-86f6-fcae2e871052",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
/*
$someObject = json_decode($response);

foreach ($someObject as $key => $val) {
if ($key == "id") {
return $val;
}
}
 */
            return $response;
        }
    }

    public function createCampaign($nombreCampaign, $idContactList, $token, $phoneColumns, $dialingMode, $queueID, $iniciaCampaign)
    {
        $body = "{\n
            \"name\": \"" . $nombreCampaign . "\",\n
            \"version\": 0,\n
            \"contactList\": {\n
                            \"id\": \"" . $idContactList . "\",\n
                            \"name\": \"\",\n
                            \"selfUri\": \"\"\n
                           },\n
            \"queue\": {\n
                        \"id\": \"" . $queueID . "\",\n
                        \"name\": \"\",\n
                        \"selfUri\": \"\"\n
                        },\n
            \"dialingMode\": \"" . $dialingMode . "\",\n
            \"script\": {\n
                        \"id\": \"cf9ab92e-f696-49bd-9d82-b26c1f76d136\",\n
                        \"name\": \"\",\n
                        \"selfUri\": \"\"\n
                       },\n
            \"edgeGroup\": {\n
                            \"id\": \"4627bd4c-b0de-4e72-8ea4-070d14907f44\",\n
                            \"name\": \"\",\n
                            \"selfUri\": \"\"\n
                            },\n
            \"site\": null,\n
            \"campaignStatus\": \"" . $iniciaCampaign . "\",\n
            \"phoneColumns\": [\n
                                " . $phoneColumns . "
                           ],\n
            \"abandonRate\": null,\n
            \"dncLists\": null,\n
            \"callableTimeSet\": null,\n
            \"callAnalysisResponseSet\": {\n
                                            \"id\": \"e9326a83-afd8-4236-a85a-a4f6ca0dfdbd\",\n
                                            \"name\": \"\",\n
                                            \"selfUri\": \"\"\n
                                         },\n
            \"callerName\": \"" . $nombreCampaign . "\",\n
            \"callerAddress\": \"5555555\",\n
            \"outboundLineCount\": null,\n
            \"ruleSets\": null,\n
            \"skipPreviewDisabled\": null,\n
            \"previewTimeOutSeconds\": null,\n
            \"alwaysRunning\": null,\n
            \"contactSort\": null,\n
            \"contactSorts\": null,\n
            \"noAnswerTimeout\": 18,\n
            \"callAnalysisLanguage\": null,\n
            \"priority\": 5,\n
            \"contactListFilters\": null\n
        }";
        //return $body;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.mypurecloud.com/api/v2/outbound/campaigns",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $token,
                "Cache-Control: no-cache",
                "Content-Type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }

    public function deleteContactList($token, $idContactList)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.mypurecloud.com/api/v2/outbound/contactlists/" . $idContactList . "",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $token,
                "Cache-Control: no-cache",
                "Content-Type: application/json",
                "Postman-Token: fd16d0fe-de41-42cd-b34d-66bce4b94f99",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }
}
