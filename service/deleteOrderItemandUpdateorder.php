<?php

include '../config.php';
include '../class/Order.php';
$configobj = new config();
$conn = $configobj->getConnection();

$orderobj = new Order();

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$order_id = $request->order_id;

echo json_encode($orderobj->deleteOrderItemandUpdateorder($conn,$order_id));

