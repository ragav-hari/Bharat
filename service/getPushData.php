<?php



 include '../config.php';
 include '../class/Order.php';
    $configobj = new config();
    $con = $configobj->getConnection();
        $obj = new Order();
         



$postdata = file_get_contents("php://input");
$request = json_decode($postdata);


$userid=$request->user_id;
$mobile=$request->user_mobile;
$orderid=$request->order_id;       
$title=$request->title;
$message=$request->message;
             

//echo json_encode(insertmessage($con,$userid,$mobile,$orderid,$title,$message));

echo json_encode($obj->insertPushMessage($con,$userid,$mobile,$orderid,$title,$message));

