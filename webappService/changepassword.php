<?php

include '../config.php';
include '../webappClass/UserClass.php';

$configobj = new config();
$conn = $configobj->getConnection();

$userobj = new UserClass();

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$user_id = $request->user_id;
$old_password = $request->old_password;
$new_password = $request->new_password;

echo json_encode($userobj->changepassword($conn,$user_id,$old_password,$new_password));
