<?php

include '../config.php';
include '../class/User.php';
$configobj = new config();
$conn = $configobj->getConnection();

$userobj = new User();

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);


$bh_user_name=$request->bh_user_name;
$user_email = $request->bh_emailid;
$user_mobileno = $request->bh_mobileno;
$user_password = $request->bh_user_password;
$user_bh_id = $request->bh_id;

echo json_encode($userobj->addEmployeeDetails($conn,$bh_user_name,$user_email,$user_mobileno,$user_password,$user_bh_id));



