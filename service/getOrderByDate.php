<?php

include '../config.php';
include '../class/Order.php';

$configobj = new config();
$con = $configobj->getConnection();
$orderobj = new Order();


$postdata = file_get_contents("php://input");
$request = json_decode($postdata);


$orderdate=$request->order_date;



echo json_encode($orderobj->getOrderByDate($con, $orderdate));



