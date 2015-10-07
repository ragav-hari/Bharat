<?php
include '../config.php';
include '../webappClass/OrderClass.php';

$configobj = new config();
$conn = $configobj->getConnection();

$orderobj = new OrderClass();

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$order_id   = $request->order_id;
$user_id    = $request->user_id;
$mobile_no  = $request->mobile_no;
$title      = $request->title;
$message    = $request->message;

echo json_encode($orderobj->insertPushMessage($conn, $user_id, $mobile_no, $order_id, $title, $message));
