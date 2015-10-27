<?php

include '../config.php';
include '../webappClass/UserClass.php';

$configobj = new config();
$conn = $configobj->getConnection();

$userobj = new UserClass();

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$codes = $request->code;
$email = $request->email;

echo json_encode($userobj->verifyCode($conn,$codes,$email));



