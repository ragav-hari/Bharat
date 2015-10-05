<?php

include '../config.php';
include '../class/Order.php';

$configobj = new config();
$con = $configobj->getConnection();
$orderobj = new Order();

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$orderid = $request->order_id;
echo json_encode($orderobj->getFullOrder($con,$orderid));