<?php

include '../config.php';
include '../class/User.php';
$configobj = new config();
$conn = $configobj->getConnection();

$userobj = new User();

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);


$user_id = $request->id;
echo json_encode($userobj->deleteEmployee($conn,$user_id));



