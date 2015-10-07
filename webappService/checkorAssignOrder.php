<?php
include '../config.php';
include '../webappClass/OrderClass.php';

$configobj = new config();
$conn = $configobj->getConnection();

$orderobj = new OrderClass();

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);


$user_id    = $request->user_id;
$order_id   = $request->order_id;

echo json_encode($orderobj->checkorAssignOrder($conn,$user_id,$order_id));
