<?php

include '../config.php';
include '../class/User.php';
$configobj = new config();
$conn = $configobj->getConnection();

$userobj = new User();
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$user_mobileno = $request->user_mobileno;

echo json_encode($userobj->getUserPreloadData($conn,$user_mobileno));
//echo "USR",$user_mobileno;
