<?php

include '../config.php';
include '../class/User.php';
$configobj = new config();
$conn = $configobj->getConnection();

$userobj = new User();
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$devicetoken = $request->deviceToken;
$mobileno = $request->mobileno;

echo json_encode($userobj->registerPush($conn,$mobileno,$devicetoken));