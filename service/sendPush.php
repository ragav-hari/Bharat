<?php

header("Content-Type: application/json");
header("X-Ionic-Application-Id: bf3dc05493a948140d5d40fd340c218dc6e15d887ddcbb71");

$obj  =  array("token"=>array("8ea4e382-3988-4049-8315-7426840bdaaf"),
               "notification"=>array("alert"=>"Hello World",
                   "android"=>array("collapseKey"=>"foo","delayWhileIdle"=>true,"timeToLive"=>300,
                       "payload"=>array("key1"=>"2483996b"))));

$url = "https://push.ionic.io/api/v1/push";  
$content = json_encode($obj);

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER,
        array("Content-type: application/json"));
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

$json_response = curl_exec($curl);

$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

if ( $status != 201 ) {
    die("Error: call to URL $url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
}


curl_close($curl);

$response = json_decode($json_response, true);
echo $response;