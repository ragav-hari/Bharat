<?php
include '../config.php';
include '../webappClass/OrderClass.php';

$configobj = new config();
$conn = $configobj->getConnection();

$orderobj = new OrderClass();

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$order_type = $request->order_type;
$date = $request->date;

echo json_encode($orderobj->getOrderByType($conn,$order_type,$date));