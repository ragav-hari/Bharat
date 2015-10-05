<?php

include '../config.php';
include '../class/User.php';
$configobj = new config();
$conn = $configobj->getConnection();

$userobj = new User();

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);


$user_first_name = $request->user_first_name;
$user_email = $request->user_email;
$user_mobileno = $request->user_mobileno;
$user_address1 = $request->user_address1;
$user_address2 = $request->user_address2;
$user_city = $request->user_city;
$user_country = $request->user_country;
$user_state = $request->user_state;
$user_pincode = $request->user_pincode;
$user_landmark = $request->user_landmark;

echo json_encode($userobj->addUserDetails($conn,$user_first_name,$user_email,$user_address1,$user_address2,$user_city,$user_state,$user_country,$user_mobileno,$user_pincode,$user_landmark));



