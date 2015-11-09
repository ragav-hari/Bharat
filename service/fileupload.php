<?php

include '../config.php';
include '../class/Order.php';

$configobj = new config();
$con = $configobj->getConnection();
$orderobj = new Order();

$target_path = "../uploads/";
$error = array();
$tmp         = explode(".", $_FILES['file']['name']);
$digits = 5;
$rand = rand(pow(10, $digits-1), pow(10, $digits)-1);
$newfilename = $rand.round(microtime(true)).".".end($tmp);

$temp=$_FILES["file"]['tmp_name'];

$target_path = $target_path . $newfilename; 


$order=$_POST["ooid"];

if(move_uploaded_file($temp,$target_path))
{
    
   echo json_encode($orderobj->generateInvoice($con,$order,$newfilename,$target_path)); 
   
}
else
{ 
    echo "failed";
    echo $_FILES['file']['error'];
}

  
 