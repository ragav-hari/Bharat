<?php

 include '../config.php';
 include '../class/Order.php';
 $configobj = new config();
 $conn = $configobj->getConnection();
 $obj = new Order();
 $postdata = file_get_contents("php://input");
 $request = json_decode($postdata);
 
 $order_id = $request->order_id;
 $status   = '105';
 
 echo json_encode($order_id->changeOrderStatus($conn,$order_id,$status));