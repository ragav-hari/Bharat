<?php

include '../config.php';
include '../webappClass/OrderClass.php';

$configobj = new config();
$conn = $configobj->getConnection();

$orderobj = new OrderClass();

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$order_id = $request->order_id;
$assign_to  = $request->assign_to;

echo json_encode($orderobj->allocOrderToEmployee($conn,$order_id,$assign_to));