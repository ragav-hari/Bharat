<?php

include '../config.php';
include '../class/Order.php';
$configobj = new config();
$conn = $configobj->getConnection();

$obj = new Order();
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$mobileno = $request->user_mobileno;
echo json_encode($obj->getAllOrderList($conn,$mobileno));

