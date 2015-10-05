<?php

include '../config.php';
include '../class/User.php';
$configobj = new config();
$conn = $configobj->getConnection();

$userobj = new User();

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);


$usertype_id = $request->user_type;
$user_name = $request->user_name;
$user_mobileno = $request->user_mobileno;
$user_dealercode = $request->user_code;
$user_email = $request->user_email;

        
echo json_encode($userobj->addUserTypeData($conn, $usertype_id, $user_name, $user_dealercode, $user_mobileno,$user_email));