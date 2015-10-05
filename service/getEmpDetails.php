<?php

include '../config.php';
include '../class/User.php';
$configobj = new config();
$conn = $configobj->getConnection();

$userobj = new User();

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);


$emp_id=$request->emp_id;
echo json_encode($userobj->getEmpDetails($conn,$emp_id));



