<?php
include '../config.php';
include '../webappClass/OrderClass.php';

$configobj = new config();
$conn = $configobj->getConnection();

$orderobj = new OrderClass();

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$date = $request->date;
$user_id = $request->user_id;

echo json_encode($orderobj->getOrdersForEmployee($conn, $date,$user_id));