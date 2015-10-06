<?php

include '../config.php';
include '../webappClass/OrderClass.php';
include '../webappClass/Constants.php';

$configobj = new config();
$con = $configobj->getConnection();
$orderobj = new OrderClass();

$target_path = "../uploads/";

$error       = array();
$filename    = $_FILES['file']['name'];
$temp        = $_FILES["file"]['tmp_name'];
$target_path = $target_path . basename($filename); 
$insert_path = "/uploads/".basename($filename);

$order_id   =   $_POST["order_id"];
$user_id    =   $_POST["user_id"];
$order_type =   $_POST["order_type"];

if(move_uploaded_file($temp,$target_path))
{
   echo json_encode($orderobj->generateInvoiceandUpdateOrder($con,$order_id,$filename,$insert_path,$user_id,$order_type)); 
}
else
{ 
    echo json_encode($_FILES['file']['error']);
}

  
 