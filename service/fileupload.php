<?php

include '../config.php';
include '../class/Order.php';

$configobj = new config();
$con = $configobj->getConnection();
$orderobj = new Order();

$target_path = "../uploads/";
$error = array();
$filename = $_FILES['file']['name'];
$temp=$_FILES["file"]['tmp_name'];

$target_path = $target_path . basename($filename); 


$order=$_POST["ooid"];

if(move_uploaded_file($temp,$target_path))
{
    
   echo json_encode($orderobj->generateInvoice($con,$order,$filename,$target_path)); 
   
}
else
{ 
    echo "failed";
    echo $_FILES['file']['error'];
}

  
 