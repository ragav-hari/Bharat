<?php

include '../config.php';
include '../webappClass/UserClass.php';

$configobj = new config();
$conn = $configobj->getConnection();

$userobj = new UserClass();

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$user_email = $request->user_email;
$user_code  = $request->user_code;

echo json_encode($userobj->verifyCode($conn,$user_email,$user_code));
