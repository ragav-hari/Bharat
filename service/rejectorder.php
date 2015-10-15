<?php

 include '../config.php';
 include '../class/Order.php';
 $configobj = new config();
 $conn = $configobj->getConnection();
 $obj = new Order();
 $postdata = file_get_contents("php://input");
 $request = json_decode($postdata);
 
 $order_id = $request->order_id;
 $status   = '109';
 
 echo json_encode($obj->changeOrderStatusofOrder($conn,$order_id,$status));