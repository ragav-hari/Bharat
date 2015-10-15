<?php

 include '../config.php';
 include '../class/Order.php';
 $configobj = new config();
 $conn = $configobj->getConnection();
 $obj = new Order();
 $postdata = file_get_contents("php://input");
 $request = json_decode($postdata);
 
 $mobile = $request->user_mobileno;
 
 echo json_encode($obj->getallordersformobile($conn,$mobile));