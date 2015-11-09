<?php

include '../config.php';
include '../webappClass/OrderClass.php';
include '../webappClass/Constants.php';

$configobj = new config();
$con = $configobj->getConnection();
$orderobj = new OrderClass();

$target_path = "../uploads/";

$error       = array();


$tmp         = explode(".", $_FILES['file']['name']);
$digits = 5;
$rand = rand(pow(10, $digits-1), pow(10, $digits)-1);
$newfilename = $rand.round(microtime(true)).".".end($tmp);
$temp        = $_FILES["file"]['tmp_name'];
$target_path = $target_path . $newfilename; 
$insert_path = "/uploads/".$newfilename;

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

  
 