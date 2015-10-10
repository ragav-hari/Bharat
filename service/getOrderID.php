<?php

include '../config.php';
include '../class/User.php';
$configobj = new config();
$conn = $configobj->getConnection();

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$mobileno = $request->user_mobileno;

$userobj = new User();
echo json_encode($userobj->createOrder($conn,$mobileno));
//echo json_encode($userobj->getOrderID($conn));


