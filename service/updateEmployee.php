<?php

include '../config.php';
include '../class/User.php';
$configobj = new config();
$conn = $configobj->getConnection();

$userobj = new User();

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);


$user_id = $request->id;
$user_bh_id = $request->bh_id;
$bh_user_name=$request->bh_name;
$user_email = $request->bh_email;
$user_mobileno = $request->bh_mobile;

echo json_encode($userobj->updateEmployee($conn,$user_id,$user_bh_id,$bh_user_name,$user_email,$user_mobileno));



