<?php

include '../config.php';
include '../class/User.php';
$configobj = new config();
$conn = $configobj->getConnection();

$userobj = new User();

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);


$bh_user_name=$request->login_name;
$bh_user_password = $request->login_password;
echo json_encode($userobj->validateEmployee($conn,$bh_user_name,$bh_user_password));



