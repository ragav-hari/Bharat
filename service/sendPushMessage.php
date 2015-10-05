<?php

    include '../config.php';
    include '../class/User.php';
    $configobj = new config();
    $conn = $configobj->getConnection();
    $userobj = new User();
    
    $mobileno = $_POST["mobileno"];
    $message = $_POST["message"];
    $title = $_POST["title"];
    
    // Replace with the real server API key from Google APIs
    $apiKey = "AIzaSyCWa_0lJ3Nne8K8Nv8Tj9JwMc-a57L0Idk";

    
    // Replace with the real client registration IDs
    $registrationIDs = array($userobj->getToken($conn, $mobileno));

    // Message to be sent


    // Set POST variables
    $url = 'https://android.googleapis.com/gcm/send';

    $fields = array(
        'registration_ids' => $registrationIDs,
        'data' => array("title"=>$title,"message" => $message ),
    );
    $headers = array(
        'Authorization: key=' . $apiKey,
        'Content-Type: application/json'
    );

    // Open connection
     $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Problem occurred: ' . curl_error($ch));
        }

        curl_close($ch);   
        echo $result."<br/>";
    echo $registrationIDs.'<br/>';
    echo $userobj->getToken($conn, $mobileno).'<br/>';
    echo json_encode($fields);
    //print_r($result);
    //var_dump($result);
?>